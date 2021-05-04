#!/usr/bin/env bash -euo pipefail
# Blackfire CLI installer.

function output {
    style_start=""
    style_end=""
    if [ "${2:-}" != "" ]; then
    case $2 in
        "success")
            style_start="\033[0;32m"
            style_end="\033[0m"
            ;;
        "error")
            style_start="\033[31;31m"
            style_end="\033[0m"
            ;;
        "info"|"warning")
            style_start="\033[33m"
            style_end="\033[39m"
            ;;
        "heading")
            style_start="\033[1;33m"
            style_end="\033[22;39m"
            ;;
    esac
    fi

    builtin echo -e "${style_start}${1}${style_end}"
}

output "Blackfire Installer" "heading"

output "\nChecking Environment" "heading"

# Check that cURL or wget is installed
downloader=""
command -v curl >/dev/null 2>&1
if [ $? == 0 ]; then
    downloader="curl"
    output "  [*] cURL is installed" "success"
else
    command -v wget >/dev/null 2>&1
    if [ $? == 0 ]; then
        downloader="wget"
        output "  [*] wget is installed" "success"
    else
        output "  [ ] ERROR: cURL or wget is required for installation" "error"
        exit 1
    fi
fi

# Check that gzip is installed
command -v gzip >/dev/null 2>&1
if [ $? == 0 ]; then
    output "  [*] Gzip is installed" "success"
else
    output "  [ ] ERROR: Gzip is required for installation" "error"
    exit 1
fi

# Start downloading the right version
output "\nDownloading the Blackfire CLI Tool" "heading"

machine=`uname -m 2>/dev/null || /usr/bin/uname -m`
if [ ${machine} == "i386" ]; then
    machine="386"
else
    machine="amd64"
fi

kernel=`uname -s 2>/dev/null || /usr/bin/uname -s`
case ${kernel} in
    "Linux"|"linux")
        kernel="linux"
        if [ ${machine} == "amd64" ]; then
            kernel="linux_static"
        fi
        ;;
    "Darwin"|"darwin")
        kernel="darwin"
        ;;
    *)
        output "OS '${kernel}' not supported" "error"
        exit 1
        ;;
esac

output "  Finding the right version for platform ${kernel}/${machine}";

url="https://blackfire.io/api/v1/releases/client/${kernel}/${machine}"
output "  Downloading ${url}";
tmp_dir=`mktemp -d`
tmp_name="blackfire-"`date +"%s"`
case $downloader in
    "curl")
        curl --fail --location "${url}" > "${tmp_dir}/${tmp_name}.tgz"
        ;;
    "wget")
        wget -q --show-progress "${url}" -O "${tmp_dir}/${tmp_name}.tgz"
        ;;
esac

if [ $? != 0 ]; then
    output "  The download failed" "error"
    exit 1
fi

output "  Uncompressing archive"
tar -C ${tmp_dir} -xzf "${tmp_dir}/${tmp_name}.tgz"

output "  Verifying binary"
verif=`cat ${tmp_dir}/blackfire.sha1`
sum=`sha1sum ${tmp_dir}/blackfire -z | awk '{print $1}'`

if [ "$verif" != "$sum" ]; then
    output "  Failed to verify the binary." "error"
    exit 1
fi


output "  Making the binary executable"
chmod 755 "${tmp_dir}/blackfire"

output "  Installing the binary under /usr/local/bin/"
binary="/usr/local/bin/blackfire"
mv "${tmp_dir}/blackfire" "${binary}" > /dev/null 2>&1
ret=$?
if [ $ret != 0 ]; then
    output "  sudo password is required to move the file:"
    mv "${tmp_dir}/blackfire" "${binary}" > /dev/null 2>&1
    ret=$?
fi

if [ $ret != 0 ]; then
    binary="${tmp_dir}/blackfire"
    output "  Failed to move the binary" "warning"
    output "  The binary was saved in ${tmp_dir}/blackfire\n"
else
    output "\nThe Blackfire CLI utility was installed successfully!\n" "success"
fi

output "Installing the PHP probe (root required)" "heading"
${binary} install-php-probe
