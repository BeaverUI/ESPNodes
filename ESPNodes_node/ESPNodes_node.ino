/* IoESP framework node code
 * 
 * Use this node code with the IoESP PHP server code
 * GitHub: https://github.com/BeaverUI/ESPNodes
 * 
 * Author:  Beaver-UI
 * Date:    2020-12-19
 * Version: 1
 *  
 * This code is built using the following libraries: 
 * - ESP library by esp8266, found on http://arduino.esp8266.com/stable/package_esp8266com_index.json
 * - ArduinoJson library by Benoit Blanchon
 * - Servo library
 * - EEPROM library
 * - HX711 library by Rob Tillaart
 * 
 * 
 * This example is built for the WeMos D1 mini, but should work for other ESPs as well.
 */

#include <EEPROM.h>
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
//#include <WiFiClient.h> // for HTTP push messages
#include <ArduinoJson.h>
#include <Servo.h>
#include <HX711.h>

// Init EEPROM addresses
// load cell scale & tare
#define EEPROM_SIZE 512
struct EEPROMdata {
  float loadcell_tare_a=0;
  float loadcell_tare_b=0;
  float loadcell_scale_a=0;
  float loadcell_scale_b=0;
} eeprom_data;

// Init WiFi
int status = WL_IDLE_STATUS;
#define WIFI_SSID "MY_SSID"
#define WIFI_PASSWORD "MY_WIFI_CODE"

// Init servo
Servo servo;
int servo_attached=0;
#define SERVO_PIN D4
#define SERVO_STEPSIZE 1

// Init HX711 load cell
HX711 loadcell;
#define LOADCELL_PIN_D D7
#define LOADCELL_PIN_SCK D8
#define LOADCELL_FILTER 0.5

// Init relay
int relay_state=0;
#define RELAY_PIN D6

// Init webserver
ESP8266WebServer webserver(80);
DynamicJsonDocument JSONdata(500);

// Status variables
int servo_ref_angle=0;
int servo_angle=0;
float loadcell_value_a=0;
float loadcell_value_b=0;



void setup() {
  delay(1000); // wait until serial port is alive after reset
  Serial.begin(115200);
  
  // Get stored data from EEPROM
  EEPROM.begin(EEPROM_SIZE); // Use Flash for EEPROM emulation
  EEPROM.get(0, eeprom_data);
  EEPROM.end();

  // Setup pins
  pinMode(RELAY_PIN,OUTPUT);
  
  // Start networking
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  // optional: fixed IP address, it is recommended to assign a fixed IP via the DHCP server instead
  // IPAddress ip(192,168,1,31); IPAddress gateway(192,168,1,1); IPAddress subnet(255,255,0,0); WiFi.config(ip, gateway, subnet);
  Serial.print("Attempting to connect");
  while (WiFi.status() != WL_CONNECTED) {Serial.print("."); delay(1000);} Serial.println("");
  Serial.println("Connected");
  Serial.println("IP address: " + IPAddressString(WiFi.localIP()));

  // Start load cell
  loadcell.begin(LOADCELL_PIN_D, LOADCELL_PIN_SCK);

  // Start web server
  webserver.on("/", handleWebRoot); // Call the 'handleWebRoot' function when a client requests URI "/"
  webserver.onNotFound(handleWebNotFound);
  webserver.begin();
}


void loop() {
  static int prev_millis=0;
  
  // handle webclient
  webserver.handleClient();

  // 5 Hz loop
  if((millis() - prev_millis >= 200) || (millis() < prev_millis)){
    prev_millis=millis();
    
    handleServo();
    handleLoadcells();
  }
}




// ===== Handles to web pages =====
void handleWebRoot() {
  // process JSON input (if present)
  if(webserver.args() > 0){
    JSONdata.clear();
    deserializeJson(JSONdata, webserver.arg("plain"));
    
    if(JSONdata.containsKey("servo_ref_angle")){
      servo_ref_angle=JSONdata["servo_ref_angle"].as<int>();
    }
    
    if(JSONdata.containsKey("servo_attached")){
      servo_attached=JSONdata["servo_attached"].as<int>();
      if(servo_attached==1){
        servo.attach(SERVO_PIN);
      }else{
        servo.detach();        
      }
    } 

    if(JSONdata.containsKey("loadcell_tare_a")){
      eeprom_data.loadcell_tare_a=JSONdata["loadcell_tare_a"].as<float>();
      EEPROM.begin(EEPROM_SIZE); // Use Flash for EEPROM emulation
      EEPROM.put(0, eeprom_data);
      EEPROM.end();
    }

    if(JSONdata.containsKey("loadcell_tare_b")){
      eeprom_data.loadcell_tare_b=JSONdata["loadcell_tare_b"].as<float>();
      EEPROM.begin(EEPROM_SIZE); // Use Flash for EEPROM emulation
      EEPROM.put(0, eeprom_data);
      EEPROM.end();
    }

    if(JSONdata.containsKey("loadcell_scale_a")){
      eeprom_data.loadcell_scale_a=JSONdata["loadcell_scale_a"].as<float>();
      EEPROM.begin(EEPROM_SIZE); // Use Flash for EEPROM emulation
      EEPROM.put(0, eeprom_data);
      EEPROM.end();
    }

    if(JSONdata.containsKey("loadcell_scale_b")){
      eeprom_data.loadcell_scale_b=JSONdata["loadcell_scale_b"].as<float>();
      EEPROM.begin(EEPROM_SIZE); // Use Flash for EEPROM emulation
      EEPROM.put(0, eeprom_data);
      EEPROM.end();
    }

    if(JSONdata.containsKey("relay_state")){
      relay_state=JSONdata["relay_state"].as<int>();
      if(relay_state==1){
        digitalWrite(RELAY_PIN,HIGH);
      }else{
        digitalWrite(RELAY_PIN,LOW);
      }
    }    
  }

  // build JSONdata
  JSONdata.clear();
  JSONdata["ip"] = IPAddressString(WiFi.localIP());
  JSONdata["ssid"] = WiFi.SSID();
  JSONdata["rssi"] = WiFi.RSSI();
  JSONdata["servo_attached"] = servo_attached;
  JSONdata["servo_angle"] = servo_angle;
  JSONdata["servo_ref_angle"] = servo_ref_angle;
  JSONdata["loadcell_value_a"] = loadcell_value_a;
  JSONdata["loadcell_value_b"] = loadcell_value_b;
  JSONdata["loadcell_tare_a"] = eeprom_data.loadcell_tare_a;
  JSONdata["loadcell_tare_b"] = eeprom_data.loadcell_tare_b;
  JSONdata["loadcell_scale_a"] = eeprom_data.loadcell_scale_a;
  JSONdata["loadcell_scale_b"] = eeprom_data.loadcell_scale_b;
  JSONdata["relay_state"] = relay_state;

  // return JSONdata
  String output;
  serializeJson(JSONdata, output);  
  webserver.send(200,"application/json", output);   // Send HTTP status 200 (Ok) and send some webpage to the browser/client
}

void handleWebNotFound(){
  webserver.send(404, "text/plain", "404: Not found"); // Send HTTP status 404 (Not Found) when there's no handler for the URI in the request
}




// ===== Handles to other functions =====
void handleLoadcells(){
  // read out load cells
  static bool loadcell_select=false;
  if(loadcell_select){
    if(loadcell.is_ready()){
      loadcell.set_gain(128); // selects sensor input A for next conversion
      loadcell_value_b=(LOADCELL_FILTER)*loadcell_value_b+(1-LOADCELL_FILTER)*((float)loadcell.read()-eeprom_data.loadcell_tare_b)*(eeprom_data.loadcell_scale_b); 
      loadcell_select=false;
    }
  }else{
    if(loadcell.is_ready()){
      loadcell.set_gain(32); // selects sensor input B for next conversion
      loadcell_value_a=(LOADCELL_FILTER)*loadcell_value_a+(1-LOADCELL_FILTER)*((float)loadcell.read()-eeprom_data.loadcell_tare_a)*(eeprom_data.loadcell_scale_a);
      loadcell_select=true;
    }
  }
}

void handleServo(){
  if(servo_angle > servo_ref_angle){
    
    if(abs(servo_angle-servo_ref_angle) < SERVO_STEPSIZE){
      servo_angle=servo_ref_angle;
    }else{
      servo_angle=servo_angle-SERVO_STEPSIZE;
    }
      
    if(!servo_attached){
      servo_attached=1;
      servo.attach(SERVO_PIN);
    }
    servo.write(servo_angle);

  }else if(servo_angle < servo_ref_angle){

    if(abs(servo_angle-servo_ref_angle) < SERVO_STEPSIZE){
      servo_angle=servo_ref_angle;
    }else{
      servo_angle=servo_angle+SERVO_STEPSIZE;
    }

    if(!servo_attached){
      servo_attached=1;
      servo.attach(SERVO_PIN);
    }
    servo.write(servo_angle);
  }  
}




// ===== Helper functions =====
String IPAddressString(IPAddress address){
 return String(address[0]) + "." + String(address[1]) + "." + String(address[2]) + "." + String(address[3]);
}
