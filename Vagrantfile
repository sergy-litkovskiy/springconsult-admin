# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::configure("2") do |config|

  config.vm.define "spring-admin", autostart: false do |spring|
    spring-admin.vm.provider :virtualbox do |vb|
      # Set configuration options for the VirtualBox image.
      vb.customize ["modifyvm", :id, "--memory", "4096", "--cpus", "2", "--ioapic", "on"]
    end
    spring-admin.vm.box = "ubuntu/trusty64"
    spring-admin.vm.hostname = "springconsult_admin.loc"
    if Vagrant::Util::Platform.windows?
      spring-admin.vm.provider :virtualbox do |vb|
        # Set configuration options for the VirtualBox image.
        vb.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate//vagrant","1"]
      end
      spring-admin.vm.synced_folder ".", "/var/www/springconsult_admin.loc", :mount_options => ["dmode=777","fmode=777"], :owner => "vagrant", :group => "vagrant"
    else
      spring-admin.vm.synced_folder ".", "/var/www/springconsult_admin.loc", :nfs => { :mount_options => ["dmode=777","fmode=777"] }
    end
    spring-admin.vm.network :private_network, ip: "192.168.50.77"

    # Run this scripts after image was runned for the first time.
    spring-admin.vm.provision :shell, path: "vagrant/shell/web.sh"
  end
end