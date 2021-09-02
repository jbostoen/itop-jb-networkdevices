# jb-networkdevices
Copyright (C) 2021 Jeffrey Bostoen

[![License](https://img.shields.io/github/license/jbostoen/iTop-custom-extensions)](https://github.com/jbostoen/iTop-custom-extensions/blob/master/license.md)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/jbostoen)
🍻 ☕


Need assistance with iTop or one of its extensions?  
Need custom development?  
Please get in touch to discuss the terms: **jbostoen.itop@outlook.com**

## What?
It's more interesting for impact analysis to have separate classes of network devices, rather than to depend on a typology to define the device.  
This extension introduces:

* Firewall
* Misc. Network Device
* Network Switch
* Router
* Wireless Access Point

It should still be compatible with:
* [jb-lnkconnectablecitoconnectableci](https://github.com/jbostoen/itop-jb-lnkconnectablecitoconnectableci)
* TeemIP


It's not to be confused with [jb-ipdevices](https://github.com/jbostoen/itop-jb-ipdevices)

As of now, the network device type (typology) is still shared among the subclasses.

## Cookbook

XML:
* redefine a class so it's now abstract
* create new child classes

## FAQ

### What happens with existing network devices?

It's **required** to either:

Option 1: delete and recreate the network devices (you can use CSV import, REST API, do it manually, ...)


Option 2: You could run some SQL-queries on the database:

```
# x = the target class (Firewall, MiscNetworkDevice, NetworkSwitch, Router, WirelessAccessPoint)
# You can make this more specific if you defined the network device type in the past to differentiate between firewall, router, wireless access point, ...
# You can perhaps also do this based on the model info.
# So you might want to adjust these update statements to be more specific!
UPDATE functionalci SET finalclass = 'x' WHERE finalclass = 'NetworkDevice';
UPDATE physicaldevice SET finalclass = 'x' WHERE finalclass = 'NetworkDevice';
UPDATE connectableci SET finalclass = 'x' WHERE finalclass = 'NetworkDevice';
UPDATE datacenterdevice SET finalclass = 'x' WHERE finalclass = 'NetworkDevice';
UPDATE networkdevice SET finalclass = 'x' WHERE finalclass = 'NetworkDevice';
```


A sample approach:

1) Derive the IDs per network device type (mind that group_concat may cut off IDs if there are too many!)

```
SELECT group_concat(networkdevice.id), t.name, t.id as typologyId FROM networkdevice 
LEFT JOIN typology AS t ON networkdevice.networkdevicetype_id = t.id 
GROUP BY networkdevicetype_id;
```

2) Move each series into its proper class

```

# Above we got the IDs per typology we had already defined

# IDs derived by previous query
set @ids = '13745,13816,13826,14172,14173,14174,14175,14176,14177,14178,14179,14180,14181';

# Adjust class!
set @nwdevtype = 'MiscNetworkDevice'; 

# Finalclass must be updated for each parent class
update networkdevice set finalclass = @nwdevtype where find_in_set(id, @ids);
update datacenterdevice set finalclass = @nwdevtype where find_in_set(id, @ids);
update connectableci set finalclass = @nwdevtype where find_in_set(id, @ids);
update physicaldevice set finalclass = @nwdevtype where find_in_set(id, @ids);
update functionalci set finalclass = @nwdevtype where find_in_set(id, @ids);

# Also insert these IDs into each proper table (adjust name!)
insert into MiscNetworkDevice (id)
select id from functionalci where find_in_set(id, @ids);


```

3) Double-check if nothing was left behind

```
# This should result in zero results
SELECT * FROM networkdevice WHERE finalclass = 'NetworkDevice';
```



You may also need to adjust the model typology!


## Credits

Icons: Flaticon License. Free for personal and commercial purpose with attribution. 
* Firewall: https://www.flaticon.com/free-icon/firewall_139735 - Attribution: Smashicons
* MiscNetworkDevice: https://www.flaticon.com/free-icon/device_2905997 - Attribution: phatplus
* NetworkSwitch: https://www.flaticon.com/free-icon/switch_1088747 - Attribution: phatplus
* Router: https://www.flaticon.com/free-icon/router_3474407 - Attribution: xnimrodx
* WirelessAccessPoint: https://www.flaticon.com/free-icon/router_3029974 - Attribution: xnimrodx

