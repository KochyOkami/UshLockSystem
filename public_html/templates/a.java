#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <FS.h>

ESP8266WebServer server(80);

const char* configFile = "/config.txt"; // Nom du fichier de configuration
const char* apSSID = "Config";   // SSID du point d'accès en mode configuration
const char* apPassword = "password";     // Mot de passe du point d'accès en mode configuration

bool shouldSaveConfig = false;

void setup() {
  // Démarrez la communication série
  Serial.begin(115200);

  // Montez le système de fichiers SPIFFS
  if (SPIFFS.begin()) {
    Serial.println("Système de fichiers SPIFFS monté avec succès.");
    
    // Lisez la configuration depuis le fichier
    if (!loadConfig()) {
      // Si le fichier de configuration n'existe pas, passez en mode configuration
      configureMode();
    }
  } else {
    Serial.println("Erreur lors du montage du système de fichiers SPIFFS.");
  }

  // Configurez les gestionnaires de routes du serveur Web
  server.on("/", HTTP_GET, handleConfigRoot);
  server.on("/save", HTTP_POST, handleConfigSave);
  
  // Démarrer le serveur Web
  server.begin();
}

void loop() {
  server.handleClient();
}

void configureMode() {
  Serial.println("Mode de configuration : Connectez-vous au point d'accès ESP8266_Config");
  WiFi.softAP(apSSID, apPassword);
  delay(100); // Laissez du temps pour que le point d'accès démarre

  // Configurez les gestionnaires de routes pour la page de configuration
  server.on("/", HTTP_GET, handleConfigRoot);
  server.on("/config", HTTP_POST, handleConfigSave);
}

void handleConfigRoot() {
  String html = "<html><body>";
  html += "<h1>Configuration WiFi</h1>";
  html += "<form method='POST' action='/config'>";
  html += "SSID: <input type='text' name='ssid'><br>";
  html += "Mot de passe: <input type='password' name='password'><br>";
  html += "<input type='submit' value='Enregistrer'>";
  html += "</form></body></html>";

  server.send(200, "text/html", html);
}

void handleConfigSave() {
  String newSSID = server.arg("ssid");
  String newPassword = server.arg("password");

  if (newSSID != "" && newPassword != "") {
    // Enregistrez la nouvelle configuration
    saveConfig(newSSID, newPassword);
    server.send(200, "text/plain", "Configuration enregistrée. Redémarrage en cours...");
    
    // Redémarrez l'ESP8266 pour appliquer la nouvelle configuration
    delay(1000);
    ESP.restart();
  } else {
    server.send(400, "text/plain", "Erreur : SSID et mot de passe doivent être remplis.");
  }
}

bool loadConfig() {
  File file = SPIFFS.open(configFile, "r");
  
  if (!file) {
    Serial.println("Impossible de charger la configuration depuis le fichier.");
    return false;
  }

  String config;
  while (file.available()) {
    config += file.readStringUntil('\n');
    config += "\n"; // Ajoutez un saut de ligne pour séparer les lignes
  }
  file.close();

  int index = config.indexOf('\n');
  if (index != -1) {
    String loadedSSID = config.substring(0, index);
    String loadedPassword = config.substring(index + 1);

    connectToWiFi(loadedSSID, loadedPassword); // Connectez-vous avec la configuration lue
    Serial.println("Configuration chargée avec succès depuis le fichier.");
    return true;
  } else {
    Serial.println("Erreur lors du chargement de la configuration depuis le fichier.");
    return false;
  }
}

void saveConfig(const String& ssid, const String& password) {
  File file = SPIFFS.open(configFile, "w");

  if (!file) {
    Serial.println("Impossible d'enregistrer la configuration dans le fichier.");
    return;
  }

  file.println(ssid);
  file.println(password);
  file.close();
  Serial.println("Configuration enregistrée dans le fichier.");
}

void connectToWiFi(const String& ssid, const String& password) {
  Serial.println("Connexion au WiFi...");
  Serial.println(ssid);
  Serial.println(password);
  WiFi.begin(ssid.c_str(), password.c_str());

  int attempt = 15;
  while (WiFi.status() != WL_CONNECTED && attempt > 0) {
    delay(1000);
    Serial.println("Connexion en cours...");
    attempt--;
  }
  
  if (WiFi.status() == WL_CONNECTED){
    Serial.println("Connecté au WiFi avec succès");
  }else{
    configureMode();
  }
}