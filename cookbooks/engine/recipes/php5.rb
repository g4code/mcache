
package 'python-software-properties'

bash 'apt_ppa' do
  code <<-EOH
    sudo add-apt-repository ppa:ondrej/php5-5.6 -y
    sudo apt-get update
    EOH
end

package 'php5'

package 'php5-dev'

package 'php5-memcached'

package 'php-pear'

bash 'php_couchbase' do
  code <<-EOH
    sudo pecl install couchbase-1.2.2
    sudo sh -c 'echo "extension=couchbase.so" > /etc/php5/mods-available/couchbase.ini'
    cd /etc/php5/cli/conf.d/ && sudo ln -s ../../mods-available/couchbase.ini 30-couchbase.ini
    EOH
end

package 'apache2' do
  action :remove
end

bash 'install_composer' do
  code <<-EOH
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
    EOH
  not_if { ::File.exists?("/usr/bin/composer") }
end