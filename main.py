import requests
from bs4 import BeautifulSoup
from pymongo import MongoClient
import time

client = MongoClient()
db = client["data"]
collection = db['most_active_stocks']


def get_data():
    headers = {'User-Agent': 'Mozilla/5.0'}
    
    r = requests.get("https://finance.yahoo.com/most-active", headers=headers)

    if (r.status_code != 200):
        r = requests.get('https://finance.yahoo.com/most-active', headers=headers)
        print (r.status_code)
        exit()


    soup = BeautifulSoup(r.text,"html.parser")
    data = []

    table = soup.find('tbody')
    rows = table.find_all('tr')
    index = 0
    for row in rows:
        cols = row.find_all('td')
        index += 1
        data.append({
            'Index': index,
            'Symbol': cols[0].get_text(),
            'Name': cols[1].get_text(),
            'Price (Introday)': cols[2].get_text(),
            'Change': cols[3].get_text(),
            'Volume': cols[5].get_text()
        })

    
        
    return data

while True:
    data = get_data()
    
    if data:
        for item in data:
            collection.update_one(
                {'Index': item['Index']}, 
                {'$set': item},  
                upsert=True 
            )
            
    time.sleep(180)
    
    
