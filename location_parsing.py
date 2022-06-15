# with open("C:/Users/weicheng/Desktop/Jun 14, 2022 at 21.txt") as f:
#     text = f.readlines()

import mysql.connector
# pip install mysql-connector-python
try:
    cnx = mysql.connector.connect(user='weicheng', password='awc020826',
                              host='150.230.127.102',
                              database='diary', auth_plugin='mysql_native_password')
except:
    exit(0)

cursor = cnx.cursor()

query = ("SELECT * FROM location")

cursor.execute(query)

for thing in cursor:
    # print(thing[1])
    raw = thing[1]
    raw = raw.split("\r\n")
    # print(raw)
    for s in raw:
        longitude = s[s.find("<")+1 : s.find(",")].removeprefix("+")
        latitude = s[s.find(",")+1 : s.find(">")].removeprefix("+")
        drift = s[s.find("+/-")+4 : s.find(" (")]
        speed = s[s.find("speed")+6 : s.find(" / course")]
        course = s[s.find("course")+7 : s.find(") @")]
        date = s[s.find("@")+2 : s.rfind(",")]
        time = s[s.rfind(",")+2 : s.rfind(",")+2+8]
        timezone = s[s.rfind(",")+2+8+1 : s.find("]")]
        
        print(longitude, latitude, drift,speed, course, date, time, timezone)
    break

    # break

try:
    cursor.close()
    cnx.close()
except:
    exit(0)
# print(text)