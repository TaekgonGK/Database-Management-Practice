import json

# load data
data = json.load(open("/home/cs143/data/nobel-laureates.json", "r"))

#create the .del files
persondel = open("Person.del","w+")
whenwheredel = open("WhenWhere.del","w+")
nobeldel = open("Nobel.del","w+")
prizedel = open("Prize.del", "w+")
affiliationdel = open("Affiliation.del", "w+")

personset = set()
whenwhereset = set()
nobelset = set()
prizeset = set()
affiliationset = set()
nobeltoprize = {}
nobelnumber = 0
for x in data["laureates"]:
    id = x["id"]

    #inputing into persondel
    try:
        givenName = x["givenName"]["en"] 
        gender = x["gender"]   
    except: 
        givenName = x["orgName"]["en"]
        gender = "NULL"
    try:
        familyName = x["familyName"]["en"]
    except:
        familyName = "NULL"
    
   
    if(id not in personset):
        personset.add(id)
        persontogether = id + "\t\"" + givenName + "\"\t\"" + familyName + "\"\t\"" + gender + "\"\n"
        persontogether = persontogether.replace("\"NULL\"", "NULL")
        persondel.write(persontogether)
    

    #inputting into whenwheredel
    try:
        birth = x["birth"]
        try:
            date = x["birth"]["date"]
        except:
            date = "NULL"
        try:
            city = x["birth"]["place"]["city"]["en"]
        except:
            city = "NULL"
        try:
            country = x["birth"]["place"]["country"]["en"]
        except:
            country = "NULL"
    except:
        try:
            founded = x["founded"]
            try:
                date = x["founded"]["date"]
            except:
                date = "NULL"
            try:
                place = x["founded"]["place"]
                try:
                    city = x["founded"]["place"]["city"]["en"]
                except:
                    city = "NULL"
                try:
                    country = x["founded"]["place"]["country"]["en"]
                except:
                    country = "NULL"
            except:
                city = "NULL"
                country = "NULL"
        except:
            date = "NULL"
            city = "NULL"
            country = "NULL"
    if(id not in whenwhereset):
        whenwhereset.add(id)
        whenwheretogether = id + "\t" + date + "\t\"" + city + "\"\t\"" + country + "\"\n"
        whenwheretogether = whenwheretogether.replace("\"NULL\"","NULL")
        whenwheredel.write(whenwheretogether)
    
    for y in x["nobelPrizes"]:
        #inputting into nobeldel
        try:
            awardYear = y["awardYear"]
        except:
            awardYear = "NULL"
        try:
            category = y["category"]["en"]
        except:
            category = "NULL"
        try:
            dateAwarded = y["dateAwarded"]
        except:
            dateAwarded = "NULL"
        try:
            motivation = y["motivation"]["en"]
        except:
            motivation = "NULL"
        nobelkey = awardYear + category
        if (nobelkey not in nobelset):
            nobelset.add(nobelkey)
            nobeltogether = str(nobelnumber) + "\t"+ awardYear + "\t\"" + category + "\"\t" + dateAwarded + "\t\"" + motivation + "\"\n"
            nobeltogether = nobeltogether.replace("\"NULL\"", "NULL")
            nobeldel.write(nobeltogether)
            nobeltoprize[nobelkey] = nobelnumber
            nobelnumber += 1
        
        #inputting into prizedel and affiliationdel
        try:
            prizeAmount  = y["prizeAmount"]
        except:
            prizeAmount = "NULL"
        try:
            sortOrder = y["sortOrder"]
        except:
            sortOrder = "NULL"
        try:
            prizeStatus = y["prizeStatus"]
        except:
            prizeStatus = "NULL"
        try:
            affiliation = y["affiliations"]
            try:
                aName = y["affiliations"][0]["name"]["en"]
            except:
                aName = "NULL"
            try:
                aCity = y["affiliations"][0]["city"]["en"]
            except:
                aCity = "NULL"
            try:
                aCountry = y["affiliations"][0]["country"]["en"]
            except:
                aCountry = "NULL"
        except:
            affiliation = "NULL"
            aName = "NULL"
            aCity = "NULL"
            aCountry = "NULL"
        prizekey = str(id) + str(nobeltoprize[nobelkey])
        if (prizekey not in prizeset):
            prizeset.add(prizekey)
            prizetogether = id + "\t" + str(nobeltoprize[nobelkey]) + "\t" + str(prizeAmount) + "\t" + sortOrder + "\t\"" + prizeStatus + "\"\t\"" + aName + "\"\n"
            prizetogether = prizetogether.replace("\"NULL\"", "NULL")
            prizedel.write(prizetogether)

        aKey = aName + aCity + aCountry
        if(aKey not in affiliationset):
            affiliationset.add(aKey)
            affiliationtogether = "\"" + aName + "\"\t\"" + aCity + "\"\t\"" + aCountry + "\"\n"
            affiliationdel.write(affiliationtogether)

            



     
    

        


# print the extracted information
#print(id + "\t" + givenName + "\t" + familyName)
