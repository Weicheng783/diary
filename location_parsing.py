# with open("C:/Users/weicheng/Desktop/Jun 14, 2022 at 21.txt") as f:
#     text = f.readlines()

import mysql.connector
# pip install mysql-connector-python
try:
    cnx = mysql.connector.connect(user='', password='',
                              host='localhost',
                              database='diary', auth_plugin='mysql_native_password')
except:
    exit(0)

cursor = cnx.cursor(buffered=True)
query = ("SELECT * FROM location")
cursor.execute(query)

for thing in cursor:
    # print(thing[1])
    name = thing[0]
    raw = thing[1]
    number = thing[2]

    raw = raw.split("\r\n")
    # print(raw)
    for s in raw:
        longitude = s[s.find("<")+1 : s.find(",")]
        if(longitude == ""):
            continue
        if(longitude[0] == "+"):
            longitude = longitude[1:]
        latitude = s[s.find(",")+1 : s.find(">")]
        if(latitude == ""):
            continue
        if(latitude[0] == "+"):
            latitude = latitude[1:]
        drift = s[s.find("+/-")+4 : s.find(" (")]
        speed = s[s.find("speed")+6 : s.find(" / course")]
        course = s[s.find("course")+7 : s.find(") @")]
        date = s[s.find("@")+2 : s.rfind(",")]
        time = s[s.rfind(",")+2 : s.rfind(",")+2+8]
        timezone = s[s.rfind(",")+2+8+1 : s.find("]")]
        datetime = date + " " + time

        add_record = ("INSERT INTO location_store "
               "(longitude, latitude, drift, speed, course, date, time, timezone, datetime) "
               "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)")
        
        record_factors = (longitude, latitude, drift, speed, course, date, time, timezone, datetime)

        try:
            cursor1 = cnx.cursor(buffered=True)
            cursor1.execute(add_record, record_factors)
            cnx.commit()
        except:
            continue

    try:
        cursor1 = cnx.cursor(buffered=True)
        delete_command = ("DELETE FROM `location` WHERE `location`.`name` = \"" + name + "\"")
        cursor1.execute(delete_command)
        # print("deleted entry:", name)
        cnx.commit()
        cursor1.close()
    except:
        continue

    # break

try:
    cursor.close()
    cnx.close()
except:
    exit(0)
# print(text)