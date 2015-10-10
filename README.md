# NAK API

##Speiseplan API
Diese Schnittstelle kann genutzt werden um alle Daten des Online-Speiseplans auszulesen.
Dafür benötigt diese Schnittstelle die php_openssl Erweiterung.
###Parameter
```
(optional) date : Ein Unix Timestamp des Samstags der Woche, die ausgelesen werden soll.
```
oder
```
(optional) year : Jahr der Woche.
(optional) week : Woche als Kalendarwoche.
```
###Rückgabewerte
Alle Tage mit jeweils zwei Mahlzeiten pro Tag. In den Mahlzeiten sind sowohl Beschreibung als auch Preis enthalten.
###Beispiel
```
http://host/getSpeiseplan.php?year=2015&week=40
```

```
{"monday":
  {"date":"28.09.",
  "meal1":
    {"description":"Gebratenes H\u00e4hnchenbrustfilet mit Reis und Champignonsauce",
    "price":"4,00 Eur"},
  "meal2":
    {"description":"Gegrillter Schafsk\u00e4se mit Tomaten, Zwiebeln, Peperoni, dazu Brot",
    "price":"3,50 Eur"}
}, [...]
```
