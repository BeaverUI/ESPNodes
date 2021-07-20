# ESPNodes
Easy to setup IoT framework based on Arduino-coded ESP nodes and a simple resposive PHP frontend.

ESPNodes is a framework, hence relies on basic PHP and Arduino (C/C++) programming skills to adjust it to your own needs. If you want something that does not require any programming, please have a look at ESP Easy (https://sourceforge.net/projects/espeasy/) and Domoticz (https://www.domoticz.com/).

The main advantage of ESPNodes is the simplicity, freedom and flexibility it provides. The integration of node and frontend allows quick and easy setup without hassle. It allows you to monitor and control things directly from any browser, such as switch your lights, open and close doors, measure temperature, weigh your chickens, just to name a few examples.


# Screenshots
![Frontend sensors page](/Screenshots/sensors.png)
![Frontend actuators page](/Screenshots/actuators.png)
![Frontend logs page](/Screenshots/logs.png)
![Frontend timers page](/Screenshots/timers.png)


# Features
ESPNodes currently has the following features:

## Node
* Update your ESP over the air, directly from the Arduino IDE!
* Read analog inputs
* Read and write digital pins
* Control relays
* Control servo motor
* Read load cells using HX711-compatible chips


## Frontend
* Easy to add nodes
* Automatic parsing of node data
* Configurable frienly name for every sensor
* Node sensors definaeable as read-only (r), read-write (w), and config (c)
* Logging with advanced plotting using Plotly
* Timers with advanced timing possibilities (e.g. sunset, sunrise, and complex date strings)
* Responsive design that is both mobile and computer friendly, using Bootswatch template (http://www.bootswatch.com)


# Planned features
The following features have been scheduled in upcoming releases:

## Node
* Add interfaces for the following sensors:
	* I2C/SPI Temperature sensors
* Event-based push message to frontend (e.g. on button press)

## Frontend
* Handle push messages from nodes


# How to use

## Requirements
* Any ESP8266 or similar development board that can be programmed from the Arduino IDE as node
* A server that runs PHP with cURL

## Configuration
* Download the ZIP file of this Git repository: https://github.com/BeaverUI/ESPNodes/archive/main.zip
* Adjust the Arduino sketch to your needs. Make sure the JSON object sent and received are complete (i.e. contain all sensor data you want to transmit). Make sure that any math is handled on the node, since the PHP frontend does currently not support math operations.
* Compile and upload the sketch to your ESP8266 (or similar) board. The author uses the LOLIN D1 mini board, for example.
Note: make sure you have configured the board correctly in the board manager of the Arduino IDE, and that you have installed all library dependencies. An up-to-date list of dependencies is included in the source code.
* Upload the PHP frontend code to your server public_html directory
* Read the frontent README for the installation and configuration instructions.
* Done! You should be able to read the ESP node from the PHP frontend page.