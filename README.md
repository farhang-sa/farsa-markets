[این متن به فارسی](https://github.com/farhang-sa/farsa-markets/README-fa.md)
### introduction
Markets is a PHP-MySql marketing platform with HTML5-Android/Java user interface. in this repo you can only see parts of it's php source code. main code is ofcourse not here :)). it can be used for various scenarios. a single shop, an small multi-store shopping system, a large marketplace with diffrest kind of shops and delivery scenarios. in summry its the ultimate shopping platform.

### dependencies 
1. Ted ( a mini mvc php framework for handling basic php env/input/output/etc )
2. Fallon ( a rich mvvm php framework for rapid development - kinda like Laravel - that uses a custom designed Mysql engine with json-based data handling. basicly it's the middleware )
3. Gaps( a 'games and apps manager' app for handling any types of apps that needs server-side ui-definitions/data/control/settings. it's also based on Fallon )

### user types 
1. An Admin user that controls the whole system ( from creating shop definitions/patterns/template and user to managing payment systems, SMS systems , user interfaces and etc ).
2. A Seller user who can create new shops, control shop's products, price, orders , delivery options and etc.
3. An Operator user that comes in handy when system is used as a marketplace for city-wide use. can manage orders, forward orders to other shops, save them for late delivery(in case of multiparted marketplace with late delivery) , override prices, generate provide list of saved orders , generate delivery maps and lists and etc.
4. And ofcourse a user to shop from it :))

### concepts 
1. templating shop types : when admin creates a shop and marks it as a pattern , sellers can create new instances of that shop. in that case all the shoe shops have the same structure. this structures can be extended by sellers with creation of new sections.
2. inheritance of shops : if a seller creates a shop ( using a pattern or NOT ) and marks it as a cloning-template , other sellers can use it as a parent and use it's products with/without changing prices. in that case we can use it like building-blocks for a bigger shops/multi-shops/marketplaces.
3. inheritance of products : when a seller creates a product, if new product's name/barcode doesn't exist in database, it saves a copy as a template in a product-templating shop , if any one else tries to create the same product(with same name/barcode), system offers the saved template.

### Client User Interface ( Android )
1. image : a marketplace with different types of shops : [im55.jpg](https://postimg.cc/XYyfrFq0) 
2. image : user selecting it's address and system showing the marketplace active area : [im55.jpg](https://postimg.cc/dZJLdNRj)
