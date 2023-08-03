<h1 align="center">mageesh/EmptyCategoryNotification-magento 2</h1>

<div align="center">
  <p>Mageesh Notification Configuration for Magento 2</p>
  <img src="https://img.shields.io/badge/magento-2.X-brightgreen.svg?logo=magento&longCache=true" alt="Supported Magento Versions" />
  
## Table of contents
<div align="left">


- [Usage](#usage)
- [Prerequisites](#prerequisites)
- [Setup](#setup)
- [Credits](#credits)

## Usage

This configuration is intended to be used as a Notification to Store Admin/Manager.
This Module helps to keep updated with the frontend events.
Empty Category Notification module comes with absolute details of the events.i.e. categoryName,categoryUrl,event Date and Time. 

<img src="https://i.imgur.com/mcBdjGf.png">

## Prerequisites

This setup assumes you are running Docker on a computer with at least 6GB of RAM allocated to Docker, a dual-core, and an SSD hard drive. 

This configuration has been tested on Mac & Linux. Windows is supported through the use of Docker on WSL.

## Setup

### Automated Setup with Composer
x
#run the following commands in the CLI.

#To Install the module in your local.
<br><br>composer require mageesh/module-emptycategorynotification
<br>bin/magento setup:upgrade
<br>bin/magento setup:di:compile


### Configuration
Go to Stores/Configuration/Mageesh/EmptyCategoryNotification/General

<img src="https://i.imgur.com/fai7aZk.png" />

set to "YES" in Enable Field.

<img src="https://i.imgur.com/bS18ove.png" />

Fill the send to,cc and sender fields.

save the configuration.

<img src="https://i.imgur.com/OTRNNMs.png" />


## Credits

### Saraswathy Shanmugam

My name is Saraswathy Shanmugam and I'm the creator of this repo. I'm a Magento Developer.
- <a href="https://www.linkedin.com/in/saraswathy-shanmugam/" target="_blank">🔗 Connect with me on LinkedIn</a>
- <a href="mailto:saraswathy.shanmugam26@gmail.com">💌 Contact me</a>
</div>
