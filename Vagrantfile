# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::configure("2") do |config|

  config.vm.define "springadmin", autostart: false do |springadmin|
    springadmin.vm.provider :virtualbox do |vb|
      # Set configuration options for the VirtualBox image.
      vb.customize ["modifyvm", :id, "--memory", "4096", "--cpus", "2", "--ioapic", "on"]
    end
    springadmin.vm.box = "ubuntu/trusty64"
    springadmin.vm.hostname = "springconsult-admin.loc"
    if Vagrant::Util::Platform.windows?
      springadmin.vm.provider :virtualbox do |vb|
        # Set configuration options for the VirtualBox image.
        vb.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate//vagrant","1"]
      end
      springadmin.vm.synced_folder ".", "/var/www/springconsult-admin.loc", :mount_options => ["dmode=777","fmode=777"], :owner => "vagrant", :group => "vagrant"
    else
      springadmin.vm.synced_folder ".", "/var/www/springconsult-admin.loc", :nfs => { :mount_options => ["dmode=777","fmode=777"] }
    end
    springadmin.vm.network :private_network, ip: "192.168.50.77"

    # Run this scripts after image was runned for the first time.
    springadmin.vm.provision :shell, path: "vagrant/shell/web.sh"
  end
end