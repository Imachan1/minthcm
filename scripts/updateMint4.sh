#!/bin/bash
repo="https://dev.evolpe.net/MintHCM/MintHCM.git"
if [ "$#" -lt 1 ]; then
        echo "Usage: $0 <instance name> [Git Branch] [Repo URL]"
        exit 1
fi
instance_dir=${1}
if [ "$#" -gt 1 ]; then
        branch=${2}
        shift
fi
if [ "$#" -gt 1 ]; then
        repo=${2}
        shift
fi
tmp_dir=$(mktemp -d -t minthcm-XXXXXXXXXX)
if [ -z "$branch" ]; then
        git clone ${repo} ${tmp_dir}
else
        git clone -b ${branch} ${repo} ${tmp_dir}
fi

echo "Aktualizacja custom\n"
cp -r ${tmp_dir}/legacy/custom/* /var/www/${instance_dir}/legacy/custom/
echo "Aktualizacja data\n"
cp -r ${tmp_dir}/legacy/data/* /var/www/${instance_dir}/legacy/data/
echo "aktualizacja include\n"
cp -r ${tmp_dir}/legacy/include/* /var/www/${instance_dir}/legacy/include/
echo "aktualizacja jssource\n"
cp -r ${tmp_dir}/legacy/jssource/* /var/www/${instance_dir}/legacy/jssource/
echo "aktualizacja metadata\n"
cp -r ${tmp_dir}/legacy/metadata/* /var/www/${instance_dir}/legacy/metadata/
echo "aktualizacja themes\n"
cp -r ${tmp_dir}/legacy/themes/* /var/www/${instance_dir}/legacy/themes/
echo "aktualizacja modules\n"
cp -r ${tmp_dir}/legacy/modules/* /var/www/${instance_dir}/legacy/modules/
echo "aktualizacja API\n"
cp -r ${tmp_dir}/legacy/Api/* /var/www/${instance_dir}/legacy/Api/
echo "aktualizacja vendor\n"
cp -r ${tmp_dir}/legacy/vendor/* /var/www/${instance_dir}/legacy/vendor/
echo "aktualizacja lib\n"
cp -r ${tmp_dir}/legacy/lib/* /var/www/${instance_dir}/legacy/lib/
echo "aktualizacja install\n"
cp -r ${tmp_dir}/legacy/install/* /var/www/${instance_dir}/legacy/install/
echo "aktualizacja MintCLI\n"
cp -r ${tmp_dir}/legacy/MintCLI/* /var/www/${instance_dir}/legacy/MintCLI/
cp ${tmp_dir}/MintCLI /var/www/${instance_dir}/MintCLI
echo "aktualizacja minthcm_version\n"
cp -r ${tmp_dir}/legacy/minthcm_version.php /var/www/${instance_dir}/legacy/minthcm_version.php
echo "aktualizacja frontend\n"
cp -r ${tmp_dir}/vue/dist/* /var/www/${instance_dir}/
echo "aktualizacja api\n"
rsync -ra ${tmp_dir}/api/ /var/www/${instance_dir}/api/ --exclude configs

echo "uprawnienia"
chown -R www-data:www-data /var/www/${instance_dir}
chmod 755 MintCLI
chown root:root MintCLI

rm -rf ${tmp_dir}