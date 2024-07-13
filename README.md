[این متن به فارسی](https://github.com/farhang-sa/farsa-markets/blob/main/README-fa.md)
### introduction
Markets is a PHP-MySql marketing platform with HTML5-Android/Java user interface. it took me 6 months to create the php/mysql backend &&& html5 frontend &&& 3 different types of android apps ( 1 client and 2 for managers and operators). in this repo you can only see parts of it's php source code. main code is ofcourse not here :)). it can be used for various scenarios. a single shop, an small multi-store shopping system, a large marketplace with diffrest kind of shops and delivery scenarios. in summry its the ultimate shopping platform.

### dependencies 
1. Ted ( a mini mvc php framework for handling basic php env/input/output/etc )
2. Fallon ( a rich mvvm php framework for rapid development - kinda like Laravel - that uses a custom designed Mysql engine with json-based data handling. basicly it's the middleware )
3. Gaps( a 'games and apps manager' app for handling any types of apps that needs server-side ui-definitions/data/control/settings. it's also based on Fallon )

### user types 
1. An Admin user that controls the whole system ( from creating shop definitions/patterns/template and user to managing payment systems, SMS systems , user interfaces and etc ).
2. A Seller user who can create new shops, control shop's products, price, orders , delivery options and etc.
3. An Operator user that comes in handy when system is used as a marketplace for city-wide use. can manage orders, forward orders to other shops, save them for late delivery(in case of multiparted marketplace with late delivery) , override prices, generate provide list of saved orders , generate delivery maps and lists and etc.
4. And ofcourse a user to shop from it :))

### Client User Interface ( Android - HTML5 )
<table>
  <thead>
    <tr>
      <th colspan=3>
        <div style='width:100%;font-weight:bold;font-size:19px;text-align:center;'>
          <b>City-Wide MarketPlace</b><br />
          location linked marketplaces for online delivery in the next day
        </div>
      </th>
    </tr>
    <tr>
      <th>view</th>
      <th>Android</th>
      <th>Web/HTML5</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <b>Entry page of a city-wide marketplace</b> <br />
        1. custom message of system for all users (blue1) <br />
        2. custom message of system for users in this marketplace's area (blue2)<br />
        3. picture with a logo is entry of the main marketplace <br />
        4. other active shops that are registered in system can be seen below 
      </td>
      <td><img src='https://i.postimg.cc/Y9bGdyvV/im1.jpg' /></td>
      <td><img src='https://i.postimg.cc/YSShhznz/im1-web.jpg' /></td>
    </tr>
    <tr>
      <td>
        <b>main-sections according to shop types <br />3 types of shops used as cloning_shops<br />3 Grocery-Stores | 2 Dairy-Stores | 2 Appliances</b><br /><br />
        user can search with text/barcode-scan/voice
      </td>
      <td><img src='https://i.postimg.cc/XYyfrFq0/im3.jpg' /></td>
      <td><img src='https://i.postimg.cc/HsmRwZwH/im3-web.jpg' /></td>
    </tr>
    <tr>
      <td>showing all sections of 2 Dairy-Stores</td>
      <td><img src='https://i.postimg.cc/3RFLw7dq/im4.jpg' /></td>
      <td><img src='https://i.postimg.cc/Y9Hnkjqg/im4-web.jpg' /></td>
    </tr>
    <tr>
      <td>
        showing products in a section<br /><br /><br />
        1. if cloned-shops have the same product , system will select the cheapest.
        2. you can sort by newest/oldest/cheapest/most expensive
        3. you can sort after search to.
      </td>
      <td><img src='https://i.postimg.cc/vTMZW78B/im5.jpg' /></td>
      <td><img src='https://i.postimg.cc/D0gy5Wmc/im5-web.jpg' /></td>
    </tr>
    <tr>
      <td>
        user selecting address and system can mark the marketplace active area<br /><br />
        #OSM #osmdroid #OpenLayers #GraphHopper #Routing <br /><br />
        it was good coding maps and routing :)
      </td>
      <td><img src='https://i.postimg.cc/bvxJdF8M/im6.jpg' /></td>
      <td><img src='https://i.postimg.cc/qRp7KXv7/im6-web.jpg' /></td>
    </tr>
  </tbody>
</table>

### Seller / Operator Apps ( Only Android )
<table>
  <tbody>
    <tr>
      <td><img src='https://i.postimg.cc/HWXgm7YZ/ia-1.jpg' /></td>
      <td><img src='https://i.postimg.cc/4NxB5rS6/ia-2.jpg' /></td>
      <td><img src='https://i.postimg.cc/Z5Kt67r2/ia-2.jpg' /></td>
    </tr>
    <tr>
      <td>
        1. listing all marketplaces/single-store/etc <br/>
        2. creating new / editing
      </td>
      <td>
        1. dstats of all orders in all shops in one view.
      </td>
      <td>
        1. basic view of a shop/marketplace in eyes of operator/seller <br />
        2. box1 - product info : total count / finished / low count / new <br />
        3. box2 - orders info : counts / total value / etc <br />
        4. box3 - debts info : how many total / how many today / value <br />
        5. box4 - more : staff managment / price changes for inflation managment <br />
      </td>
    </tr>
    <tr>
      <td><img src='https://i.postimg.cc/157MYJkL/ia-4.jpg' /></td>
      <td><img src='https://i.postimg.cc/qMGHhVC2/ia-3.jpg' /></td>
      <td><img src='https://i.postimg.cc/qvbfwswR/ia-4.jpg' /></td>
    </tr>
    <tr>
      <td>
        orders listing <br />
        1. search / catagory as progress <br />
        2. order details <br />
        3. three button on top only for Operators <br />
        3.1. for marketplaces to create provide list<br />
        3.2. for createing delivery list in detail<br />
        3.3. for generating map view of all orders for delivery<br />
      </td>
      <td>
        Sample of a provide list for a marketplace<br />
        it contains items of all orders that Operator must provide and box <be />
      </td>
      <td>
        Sample of a delivery map for 3 orders!
      </td>
    </tr>
  </tbody>
</table>
