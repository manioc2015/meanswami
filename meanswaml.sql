begin trans;

DROP TABLE IF EXISTS "attribute_groups";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "attribute_groups" (
  "id" int  NOT NULL,
  "attribute_group" varchar(63) NOT NULL DEFAULT '',
  "priority" smallint  NOT NULL DEFAULT '0',
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "attributes";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "attributes" (
  "id" int  NOT NULL,
  "attribute_group_id" int  NOT NULL,
  "attribute_value" varchar(63) NOT NULL DEFAULT '',
  "priority" smallint  NOT NULL DEFAULT '0',
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_attributes_group_id_attribute_id";
CREATE INDEX idx_attributes_id_attribute_id ON attributes ("attribute_group_id","id");

DROP TABLE IF EXISTS "client_payment_methods";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "client_payment_methods" (
  "id" serial  NOT NULL,
  "client_id" int  NOT NULL,
  "name" varchar(31) NOT NULL DEFAULT '',
  "payment_method_last_4" char(4) NOT NULL DEFAULT '0000',
  "cc_number" varchar(127) NOT NULL DEFAULT '',
  "expdate" varchar(127) NOT NULL DEFAULT '',
  "address" varchar(31) NOT NULL DEFAULT '',
  "zipcode" varchar(15) NOT NULL DEFAULT '',
  "priority" smallint NOT NULL,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_client_payment_methods_client_id_priority";
CREATE INDEX idx_client_payment_methods_client_id_priority ON client_payment_methods ("client_id","priority");

DROP TABLE IF EXISTS "client_properties";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "client_properties" (
  "id" serial  NOT NULL,
  "client_id" int  NOT NULL,
  "property_id" int  NOT NULL,
  "property_type" property_type NOT NULL DEFAULT 'Restaurant',
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_client_properties_property_id_property_type_active_client_id";
CREATE INDEX idx_client_properties_property_id_property_type_active_client_id ON client_properties ("property_id","property_type","deleted_at","client_id");

DROP TABLE IF EXISTS "clients";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "clients" (
  "id" serial  NOT NULL,
  "user_id" bigint  NOT NULL,
  "client_name" varchar(63) NOT NULL DEFAULT '',
  "business_name" varchar(63) NOT NULL DEFAULT '',
  "address1" varchar(127) NOT NULL DEFAULT '',
  "address2" varchar(127) NOT NULL DEFAULT '',
  "city" varchar(127) NOT NULL DEFAULT '',
  "state" char(2) NOT NULL DEFAULT '',
  "zipcode" varchar(15) NOT NULL DEFAULT '',
  "country" char(2) NOT NULL DEFAULT 'US',
  "phone1" varchar(22) NOT NULL,
  "phone2" varchar(22) NOT NULL,
  "billing_method" varchar(31) NOT NULL DEFAULT 'MONTHLY_CC',
  "status" varchar(31) NOT NULL DEFAULT 'PENDING',
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_unique_clients_user_id";
create unique index idx_unique_clients_user_id on clients (user_id);

DROP TABLE IF EXISTS "comments";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "comments" (
  "id" bigserial  NOT NULL,
  "review_id" int  NOT NULL,
  "user_id" bigint  NOT NULL,
  "comment" text NOT NULL,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_comments_review_id_active_user_id";
CREATE INDEX idx_comments_review_id_active_user_id ON comments ("review_id","user_id","deleted_at");

DROP TABLE IF EXISTS "franchises";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "franchises" (
  "id" serial  NOT NULL,
  "franchise_name" varchar(127) NOT NULL DEFAULT '',
  max_menu_items int not null default 1,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "franchises_ignore";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "franchises_ignore" (
  id int not null,
  "franchise_id" int  NOT NULL,
  "user_id" bigint  NOT NULL,
  PRIMARY KEY (id)
);

CREATE INDEX idx_franchises_ignore_franchise_id_user_id ON franchises_ignore ("franchise_id", "user_id");

DROP TABLE IF EXISTS "invoices";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "invoices" (
  "id" serial  NOT NULL,
  "client_id" int  NOT NULL,
  "invoice_number" varchar(22) NOT NULL DEFAULT '',
  "status" varchar(31) NOT NULL DEFAULT 'PENDING',
  "invoice_details" text NOT NULL,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_invoices_invoice_number";
CREATE UNIQUE INDEX idx_invoices_invoice_number ON invoices ("invoice_number");

DROP TABLE IF EXISTS "menu_item_attribute";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "menu_item_attribute" (
  id bigserial not null,
  "menu_item_id" bigint  NOT NULL,
  "attribute_id" int  NOT NULL,
  "attribute_group_id" int NOT NULL,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY (id)
);

CREATE INDEX idx_menu_item_attribute_menu_item_id_attribute_id ON menu_item_attribute ("menu_item_id", "attribute_id", "attribute_group_id");


DROP TABLE IF EXISTS "menu_item_intersects";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "menu_item_intersects" (
  "menu_item_id1" bigint  NOT NULL,
  "menu_item_id2" bigint  NOT NULL,
  "num_intersects" int NOT NULL,
  PRIMARY KEY ("menu_item_id1","menu_item_id2")
);

DROP TABLE IF EXISTS "menu_item_prices";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "menu_item_prices" (
  "id" bigserial  NOT NULL,
  "menu_item_id" bigint  NOT NULL,
  "min_price" decimal(6,2)  DEFAULT NULL,
  "max_price" decimal(6,2)  DEFAULT NULL,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_menu_item_prices_menu_item_id";
CREATE INDEX idx_menu_item_prices_menu_item_id ON menu_item_prices ("menu_item_id");

DROP TABLE IF EXISTS "menu_items";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "menu_items" (
  "id" bigserial  NOT NULL,
  "property_id" int  NOT NULL,
  "property_type" property_type NOT NULL DEFAULT 'Restaurant',
  "name" varchar(127) NOT NULL DEFAULT '',
  "tagline" varchar(255) NOT NULL DEFAULT '',
  "main_ingredients" text NOT NULL,
  "is_test_item" smallint  NOT NULL DEFAULT '0',
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  "active" boolean NOT NULL DEFAULT true,
  availability json,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_menu_items_active_is_test_item_created_datetime";
CREATE INDEX idx_menu_items_active_is_test_item_created_datetime ON menu_items ("deleted_at","is_test_item","created_at");

DROP INDEX IF EXISTS "idx_menu_items_property_id_property_type";
CREATE INDEX idx_menu_items_property_id_property_type ON menu_items ("property_id","property_type");


DROP TABLE IF EXISTS "ratings";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "ratings" (
  "id" bigserial  NOT NULL,
  "user_id" bigint  NOT NULL,
  "menu_item_id" bigint  NOT NULL,
  "rating" smallint  NOT NULL,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_ratings_menu_item_id_user_id_rating_datetime";
CREATE UNIQUE INDEX idx_ratings_menu_item_id_user_id_rating_datetime ON ratings ("menu_item_id","user_id","created_at");

DROP TABLE IF EXISTS "restaurants";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "restaurants" (
  "id" serial  NOT NULL,
  "franchise_id" int  NOT NULL DEFAULT '0',
  "sp_listing_id" varchar(127) NOT NULL DEFAULT '',
  "yp_listing_id" int NOT NULL DEFAULT '0',
  "yelp_listing_id" varchar(127) NOT NULL DEFAULT '',
  "name" varchar(127) NOT NULL,
  "address1" varchar(127) NOT NULL,
  "address2" varchar(127) NOT NULL,
  "cross_streets" varchar(127) NOT NULL,
  "city" varchar(127) NOT NULL,
  "state" char(2) NOT NULL,
  "zipcode" varchar(15) NOT NULL,
  "country" char(2) NOT NULL DEFAULT 'US',
  "lat" decimal(16,13) DEFAULT NULL,
  "lon" decimal(16,13) DEFAULT NULL,
  "phone" varchar(22) NOT NULL,
  "website" varchar(127) NOT NULL,
  "description" text NOT NULL,
  "email" varchar(63) NOT NULL,
  "open_hours" varchar(255) NOT NULL,
  "payment_methods" varchar(63) NOT NULL,
  "timezone" varchar(63) NOT NULL DEFAULT 'UTC',
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  status varchar(31) NOT NULL DEFAULT 'PENDING_APPROVAL',
  is_claimed_on_yelp boolean,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_restaurants_sp_listing_id";
CREATE INDEX idx_restaurants_sp_listing_id ON restaurants ("sp_listing_id");
DROP INDEX IF EXISTS "idx_restaurants_yp_listing_id";
CREATE INDEX idx_restaurants_yp_listing_id ON restaurants ("yp_listing_id");
DROP INDEX IF EXISTS "idx_restaurants_yelp_listing_id";
CREATE INDEX idx_restaurants_yelp_listing_id ON restaurants ("yelp_listing_id");
DROP INDEX IF EXISTS "idx_restaurants_phone";
CREATE INDEX idx_restaurants_phone ON restaurants ("phone");

DROP TABLE IF EXISTS "restaurants_ignore";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "restaurants_ignore" (
  id bigint not null,
  "restaurant_id" int  NOT NULL,
  "user_id" bigint  NOT NULL,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY (id)
);

CREATE INDEX idx_restaurants_ignore_restaurant_id_user_id ON restaurants_ignore ("restaurant_id", "user_id");

DROP TABLE IF EXISTS "reviews";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "reviews" (
  "id" int  NOT NULL,
  "rating_id" bigint  NOT NULL,
  "review" text NOT NULL,
  "num_likes" int  NOT NULL DEFAULT '0',
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_reviews_rating_id_active";
CREATE INDEX idx_reviews_rating_id_active ON reviews ("rating_id","deleted_at");

DROP TABLE IF EXISTS "user_locations";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_locations" (
  "id" bigserial  NOT NULL,
  "name" varchar(127) NOT NULL DEFAULT '',
  "user_id" bigint  NOT NULL,
  "lat" decimal(16,13) DEFAULT NULL,
  "lon" decimal(16,13) DEFAULT NULL,
  "radius" decimal(5,2) DEFAULT '5.00',
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_user_locations_user_id_active";
CREATE INDEX idx_user_locations_user_id_active ON user_locations ("user_id","deleted_at");

DROP TABLE IF EXISTS "user_menu_item_history";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_menu_item_history" (
  id bigint not null,
  "user_id" bigint  NOT NULL,
  "menu_item_id" bigint  NOT NULL,
  action varchar(31) NOT NULL DEFAULT 'VIEWED',
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY (id)
);

CREATE INDEX idx_user_menu_item_history_user_id_menu_item_id_action ON user_menu_item_history ("user_id","menu_item_id",action);

DROP TABLE IF EXISTS "user_search_times";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_search_times" (
  "id" bigint  NOT NULL,
  "user_search_times_group_id" bigint  NOT NULL,
  "weekdaynum" smallint  DEFAULT NULL,
  "search_time_start" time with time zone DEFAULT NULL,
  "search_time_end" time with time zone DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_user_search_times_user_search_times_group_id";
CREATE INDEX idx_user_search_times_user_search_times_group_id ON user_search_times ("user_search_times_group_id", "weekdaynum", "search_time_start", "search_time_end");

DROP TABLE IF EXISTS "user_search_times_groups";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_search_times_groups" (
  "id" bigint  NOT NULL,
  "user_id" bigint  NOT NULL,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_user_search_times_groups_user_id_active";
CREATE INDEX idx_user_search_times_groups_user_id_active ON user_search_times_groups ("user_id","deleted_at");

DROP TABLE IF EXISTS "user_searches";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_searches" (
  "id" bigserial  NOT NULL,
  "name" varchar(127) NOT NULL DEFAULT '',
  "user_id" bigint  NOT NULL,
  "user_location_id" bigint  DEFAULT NULL,
  "user_search_times_group_id" bigint  DEFAULT NULL,
  "query" text NOT NULL,
  "notification_type" varchar(31) NOT NULL DEFAULT 'NONE',
  "status" VARCHAR(31) NOT NULL DEFAULT 'ACTIVE',
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL,
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_user_searches_user_id_user_search_times_group_id_user_location_id";
CREATE INDEX idx_user_searches_user_id_user_search_times_group_id_user_location_id ON user_searches ("user_id","user_search_times_group_id","user_location_id");

DROP TABLE IF EXISTS sp_restaurants;

CREATE TABLE "sp_restaurants" (
  "id" serial  NOT NULL,
  "sp_listing_id" varchar(127) NOT NULL DEFAULT '',
  "name" varchar(127) NOT NULL,
  "address1" varchar(127) NOT NULL,
  "address2" varchar(127) NOT NULL,
  "city" varchar(127) NOT NULL,
  "state" char(2) NOT NULL,
  "zipcode" varchar(15) NOT NULL,
  "country" char(2) NOT NULL DEFAULT 'US',
  "lat" decimal(16,13) DEFAULT NULL,
  "lon" decimal(16,13) DEFAULT NULL,
  "phone" varchar(22) NOT NULL,
  "category" varchar(31) NOT NULL,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" timestamp DEFAULT NULL
  PRIMARY KEY ("id")
);

DROP INDEX IF EXISTS "idx_sp_restaurants_sp_listing_id";
CREATE UNIQUE INDEX idx_sp_restaurants_sp_listing_id ON sp_restaurants ("sp_listing_id");

DROP INDEX IF EXISTS "idx_sp_restaurants_phone";
CREATE INDEX idx_sp_restaurants_phone ON sp_restaurants ("phone", signed_up);

INSERT INTO attribute_groups (id, attribute_group, priority) VALUES (1, 'Cuisine', 1);
INSERT INTO attribute_groups (id, attribute_group, priority) VALUES (2, 'Organic Ingredients', 2);
INSERT INTO attribute_groups (id, attribute_group, priority) VALUES (3, 'Diets', 3);
INSERT INTO attribute_groups (id, attribute_group, priority) VALUES (4, 'Food Allergens', 4);
INSERT INTO attribute_groups (id, attribute_group, priority) VALUES (5, 'Spicy', 5);

INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (200, 1, 'American', 0);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (201, 1, 'BBQ', 10);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (202, 1, 'Belgian', 11);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (203, 1, 'Brazilian', 20);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (204, 1, 'Cajun', 30);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (205, 1, 'Cambodian', 40);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (206, 1, 'Chinese', 50);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (207, 1, 'Cuban', 60);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (208, 1, 'English', 61);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (209, 1, 'Filipino', 70);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (210, 1, 'French', 80);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (211, 1, 'German', 90);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (212, 1, 'Greek', 100);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (213, 1, 'Indian', 110);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (214, 1, 'Irish', 120);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (215, 1, 'Italian', 130);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (216, 1, 'Japanese', 220);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (217, 1, 'Korean', 230);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (218, 1, 'Latin', 240);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (219, 1, 'Lebanese', 250);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (220, 1, 'Malaysian', 260);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (221, 1, 'Mediterranean', 271);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (222, 1, 'Mexican', 280);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (223, 1, 'Middle Eastern', 290);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (224, 1, 'Moroccan', 300);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (225, 1, 'Nordic', 310);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (226, 1, 'Pakistani', 320);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (227, 1, 'Peruvian', 330);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (228, 1, 'Polish', 340);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (229, 1, 'Russian', 350);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (230, 1, 'Southern / Soul', 360);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (231, 1, 'Spanish', 370);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (232, 1, 'Taiwanese', 380);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (233, 1, 'Cantonese', 390);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (234, 1, 'Szechuan', 400);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (235, 1, 'Hunan', 410);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (236, 1, 'Baltic', 420);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (237, 1, 'Scottish', 430);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (238, 1, 'Thai', 440);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (239, 1, 'Turkish', 450);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (240, 1, 'Vietnamese', 460);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (241, 1, 'Argentinian', 470);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (243, 1, 'Australian', 490);

INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (1, 2, 'None', 0);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (2, 2, 'Some Ingredients', 10);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (3, 2, 'Main Ingredients', 20);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (4, 2, 'All Ingredients', 30);

INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (11, 4, 'Corn', 10);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (12, 4, 'Dairy', 20);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (13, 4, 'Eggs', 30);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (14, 4, 'Fish', 40);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (15, 4, 'Gelatin', 50);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (16, 4, 'Gluten', 60);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (17, 4, 'Meats', 61);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (18, 4, 'MSG', 62);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (19, 4, 'Peanuts', 70);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (20, 4, 'Seeds', 80);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (21, 4, 'Shellfish', 90);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (22, 4, 'Soy', 100);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (24, 4, 'Spices', 110);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (25, 4, 'Tree Nuts', 120);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (26, 4, 'Wheat', 130);

INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (101, 3, 'Low-Calorie', 10);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (103, 3, 'Low-Carb', 11);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (103, 3, 'Low-Cholesterol', 20);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (104, 3, 'Low-Fat', 30);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (105, 3, 'Low-Glycemic', 40);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (106, 3, 'Low-Protein', 50);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (107, 3, 'Low-Sodium', 60);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (108, 3, 'Halal', 61);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (109, 3, 'Ital', 70);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (110, 3, 'Kosher', 80);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (111, 3, 'Vegan', 90);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (112, 3, 'Vegeterian', 100);

INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (302, 5, 'Yes', 10);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (303, 5, 'No', 20);
INSERT INTO attributes (id, attribute_group_id, attribute_value, priority) VALUES (304, 5, 'Optional', 30);

drop table if exists sp_categories;

create table sp_categories (
	name varchar(63) not null,
	primary key (name)
);

commit;
