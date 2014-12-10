# -*- mode: ruby -*-

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.network "public_network"
  config.vm.synced_folder "./", "/var/www", id: "vagrant-root", mount_options: ["dmode=775,fmode=774"]

  config.vm.provider "virtualbox" do |v|
    v.memory = 512
    v.cpus = 1
  end

  config.vm.network "forwarded_port", guest: 80, host: 80, auto_correct: true
  config.vm.network "forwarded_port", guest: 3306, host: 3307, auto_correct: true
  config.vm.network "forwarded_port", guest: 9200, host: 9200, auto_correct: true

  config.vm.provision "shell", path: "provision.sh"
end