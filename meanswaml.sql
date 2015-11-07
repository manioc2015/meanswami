DROP TABLE IF EXISTS "ad_slot_times";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "ad_slot_times" (
  "ad_slot_id" bigint  NOT NULL,
  "weekdaynum" smallint  NOT NULL,
  "start_time" time with time zone DEFAULT NULL,
  "end_time" time with time zone DEFAULT NULL
);

DROP INDEX IF EXISTS "idx_ad_slot_times";
CREATE INDEX idx_ad_slot_times ON ad_slot_times ("weekdaynum","start_time","end_time");

DROP TABLE IF EXISTS "ad_slots";

CREATE TABLE "ad_slots" (
  "ad_slot_id" bigserial  NOT NULL,
  "restaurant_id" int  NOT NULL,
  "priority" smallint NOT NULL DEFAULT '0',
  "created_datetime" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "inactive_datetime" timestamp DEFAULT NULL,
  PRIMARY KEY ("ad_slot_id")
);

DROP INDEX IF EXISTS "idx_ad_slots_restaurant_id";
CREATE INDEX idx_ad_slots_restaurant_id ON ad_slots ("restaurant_id");

DROP INDEX IF EXISTS "idx_ad_slots_inactive_priority_restaurant_id";
CREATE INDEX idx_ad_slots_inactive_priority_restaurant_id ON ad_slots ("inactive_datetime" NULLS FIRST,"priority","restaurant_id");

DROP TABLE IF EXISTS "attribute_groups";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "attribute_groups" (
  "attribute_group_id" serial  NOT NULL,
  "attribute_group" varchar(63) NOT NULL DEFAULT '',
  "priority" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("attribute_group_id")
);

DROP TABLE IF EXISTS "attributes";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "attributes" (
  "attribute_id" serial  NOT NULL,
  "attribute_group_id" int  NOT NULL,
  "attribute_value" varchar(63) NOT NULL DEFAULT '',
  "priority" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("attribute_id")
);

DROP INDEX IF EXISTS "idx_attribute_group_id_attribute_id";
CREATE INDEX idx_attribute_group_id_attribute_id ON attributes ("attribute_group_id","attribute_id");

DROP TABLE IF EXISTS "client_payment_methods";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "client_payment_methods" (
  "client_billing_info_id" serial  NOT NULL,
  "client_id" int  NOT NULL,
  "payment_method_name" varchar(31) NOT NULL DEFAULT '',
  "payment_method_last_4" char(4) NOT NULL DEFAULT '0000',
  "payment_method_cc_number" varchar(127) NOT NULL DEFAULT '',
  "payment_method_expdate" varchar(127) NOT NULL DEFAULT '',
  "payment_method_address" varchar(31) NOT NULL DEFAULT '',
  "payment_method_zipcode" varchar(6) NOT NULL DEFAULT '',
  "priority" smallint NOT NULL,
  PRIMARY KEY ("client_billing_info_id")
);

DROP INDEX IF EXISTS "idx_client_payment_methods_client_id_priority";
CREATE INDEX idx_client_payment_methods_client_id_priority ON client_payment_methods ("client_id","priority");

DROP TABLE IF EXISTS "client_properties";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "client_properties" (
  "client_property_id" serial  NOT NULL,
  "client_id" int  NOT NULL,
  "property_id" int  NOT NULL,
  "property_type" property_type NOT NULL DEFAULT 'RESTAURANT',
  "created_datetime" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "active" boolean NOT NULL DEFAULT TRUE,
  PRIMARY KEY ("client_property_id")
);

DROP INDEX IF EXISTS "idx_client_properties_property_id_property_type_active_client_id";
CREATE INDEX idx_client_properties_property_id_property_type_active_client_id ON client_properties ("property_id","property_type","active","client_id");

DROP TABLE IF EXISTS "clients";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "clients" (
  "client_id" serial  NOT NULL,
  "user_id" bigint  NOT NULL,
  "client_name" varchar(63) NOT NULL DEFAULT '',
  "client_email" varchar(255) NOT NULL DEFAULT '',
  "business_name" varchar(63) NOT NULL DEFAULT '',
  "address1" varchar(127) NOT NULL DEFAULT '',
  "address2" varchar(127) NOT NULL DEFAULT '',
  "city" varchar(127) NOT NULL DEFAULT '',
  "state" char(2) NOT NULL DEFAULT '',
  "province" varchar(127) NOT NULL DEFAULT '',
  "zipcode" varchar(6) NOT NULL DEFAULT '',
  "country" char(2) NOT NULL DEFAULT 'US',
  "phone1" varchar(22) NOT NULL,
  "phone2" varchar(22) NOT NULL,
  "billing_method" varchar(31) NOT NULL DEFAULT 'MONTHLY_CC',
  "status" varchar(31) NOT NULL DEFAULT 'PENDING',
  PRIMARY KEY ("client_id")
);

DROP INDEX IF EXISTS "idx_clients_user_id_status";
CREATE INDEX idx_clients_user_id_status ON clients ("user_id", "status");

DROP TABLE IF EXISTS "comments";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "comments" (
  "comment_id" bigserial  NOT NULL,
  "review_id" int  NOT NULL,
  "user_id" bigint  NOT NULL,
  "comment" text NOT NULL,
  "comment_datetime" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "active" boolean  NOT NULL DEFAULT TRUE,
  PRIMARY KEY ("comment_id")
);

DROP INDEX IF EXISTS "idx_comments_review_id_active_user_id";
CREATE INDEX idx_comments_review_id_active_user_id ON comments ("review_id","active","user_id");

DROP TABLE IF EXISTS "franchise_restaurants";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "franchise_restaurants" (
  "franchise_id" int  NOT NULL,
  "restaurant_id" int  NOT NULL,
  PRIMARY KEY ("franchise_id","restaurant_id")
);

DROP TABLE IF EXISTS "franchises";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "franchises" (
  "franchise_id" int  NOT NULL,
  "franchise_name" varchar(127) NOT NULL DEFAULT '',
  PRIMARY KEY ("franchise_id")
);

DROP TABLE IF EXISTS "franchises_ignore";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "franchises_ignore" (
  "franchise_id" int  NOT NULL,
  "user_id" bigint  NOT NULL,
  PRIMARY KEY ("user_id","franchise_id")
);

DROP TABLE IF EXISTS "invoices";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "invoices" (
  "invoice_id" serial  NOT NULL,
  "client_id" int  NOT NULL,
  "invoice_number" varchar(22) NOT NULL DEFAULT '',
  "invoice_datetime" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "status" varchar(31) NOT NULL DEFAULT 'PENDING',
  "invoice_details" text NOT NULL,
  PRIMARY KEY ("invoice_id")
);

DROP INDEX IF EXISTS "idx_invoices_invoice_number";
CREATE UNIQUE INDEX idx_invoices_invoice_number ON invoices ("invoice_number");

DROP TABLE IF EXISTS "menu_item_ad_slots";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "menu_item_ad_slots" (
  "menu_item_id" bigint  NOT NULL,
  "ad_slot_id" bigint  NOT NULL,
  PRIMARY KEY ("ad_slot_id","menu_item_id")
);

DROP TABLE IF EXISTS "menu_item_attribute_assignments";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "menu_item_attribute_assignments" (
  "menu_item_id" bigint  NOT NULL,
  "attribute_id" int  NOT NULL,
  PRIMARY KEY ("attribute_id","menu_item_id")
);

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
  "menu_item_price_id" bigserial  NOT NULL,
  "menu_item_id" bigint  NOT NULL,
  "property_id" int  NOT NULL,
  "property_type" property_type NOT NULL DEFAULT 'RESTAURANT',
  "min_price" decimal(6,2)  DEFAULT NULL,
  "max_price" decimal(6,2)  DEFAULT NULL,
  PRIMARY KEY ("menu_item_price_id")
);

DROP INDEX IF EXISTS "idx_menu_item_prices_menu_item_id_property_id_property_type";
CREATE UNIQUE INDEX idx_menu_item_prices_menu_item_id_property_id_property_type ON menu_item_prices ("menu_item_id","property_id","property_type");

DROP TABLE IF EXISTS "menu_items";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "menu_items" (
  "menu_item_id" bigserial  NOT NULL,
  "property_id" int  NOT NULL,
  "property_type" property_type NOT NULL DEFAULT 'RESTAURANT',
  "menu_item_name" varchar(127) NOT NULL DEFAULT '',
  "tagline" varchar(255) NOT NULL DEFAULT '',
  "description" text NOT NULL,
  "main_ingredients" text NOT NULL,
  "created_datetime" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "active" boolean  NOT NULL DEFAULT TRUE,
  "is_test_item" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("menu_item_id")
);

DROP INDEX IF EXISTS "idx_menu_items_active_is_test_item_created_datetime";
CREATE INDEX idx_menu_items_active_is_test_item_created_datetime ON menu_items ("active","is_test_item","created_datetime");

DROP INDEX IF EXISTS "idx_menu_items_property_id_property_type";
CREATE INDEX idx_menu_items_property_id_property_type ON menu_items ("property_id","property_type");

DROP TABLE IF EXISTS "menu_items_ignore";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "menu_items_ignore" (
  "menu_item_id" bigint  NOT NULL,
  "user_id" bigint  NOT NULL,
  PRIMARY KEY ("user_id","menu_item_id")
);

DROP TABLE IF EXISTS "ratings";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "ratings" (
  "rating_id" bigserial  NOT NULL,
  "user_id" bigint  NOT NULL,
  "menu_item_id" bigint  NOT NULL,
  "rating" smallint  NOT NULL,
  "rating_datetime" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("rating_id")
);

DROP INDEX IF EXISTS "idx_ratings_menu_item_id_user_id_rating_datetime";
CREATE UNIQUE INDEX idx_ratings_menu_item_id_user_id_rating_datetime ON ratings ("menu_item_id","user_id","rating_datetime");

DROP TABLE IF EXISTS "restaurants";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "restaurants" (
  "restaurant_id" serial  NOT NULL,
  "franchise_id" int  NOT NULL DEFAULT '0',
  "sp_listing_id" varchar(127) NOT NULL DEFAULT '',
  "yp_listing_id" int NOT NULL DEFAULT '0',
  "yelp_listing_id" varchar(127) NOT NULL DEFAULT '',
  "name" varchar(127) NOT NULL,
  "address" varchar(127) NOT NULL,
  "city" varchar(127) NOT NULL,
  "state" char(2) NOT NULL,
  "province" varchar(127) NOT NULL DEFAULT '',
  "zipcode" varchar(10) NOT NULL,
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
  "active" smallint  NOT NULL DEFAULT '1',
  "last_updated" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("restaurant_id")
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
  "restaurant_id" int  NOT NULL,
  "user_id" bigint  NOT NULL,
  PRIMARY KEY ("user_id","restaurant_id")
);

DROP TABLE IF EXISTS "reviews";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "reviews" (
  "review_id" int  NOT NULL,
  "rating_id" bigint  NOT NULL,
  "review" text NOT NULL,
  "num_likes" int  NOT NULL DEFAULT '0',
  "active" boolean  NOT NULL DEFAULT TRUE,
  PRIMARY KEY ("review_id")
);

DROP INDEX IF EXISTS "idx_reviews_rating_id_active";
CREATE INDEX idx_reviews_rating_id_active ON reviews ("rating_id","active");

DROP TABLE IF EXISTS "user_locations";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_locations" (
  "user_location_id" bigserial  NOT NULL,
  "user_location_name" varchar(127) NOT NULL DEFAULT '',
  "user_id" bigint  NOT NULL,
  "lat" decimal(16,13) DEFAULT NULL,
  "lon" decimal(16,13) DEFAULT NULL,
  "radius" decimal(5,2) DEFAULT '5.00',
  active boolean not null default TRUE,
  PRIMARY KEY ("user_location_id")
);

DROP INDEX IF EXISTS "idx_user_locations_user_id_active";
CREATE INDEX idx_user_locations_user_id_active ON user_locations ("user_id","active");

DROP TABLE IF EXISTS "user_menu_item_history";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_menu_item_history" (
  "user_id" bigint  NOT NULL,
  "menu_item_id" bigint  NOT NULL,
  "view_datetime" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("user_id","menu_item_id")
);

DROP TABLE IF EXISTS "user_search_times";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_search_times" (
  "user_search_times_id" bigint  NOT NULL,
  "user_search_times_group_id" bigint  NOT NULL,
  "weekdaynum" smallint  DEFAULT NULL,
  "search_time" time with time zone DEFAULT NULL,
  PRIMARY KEY ("user_search_times_id")
);

DROP INDEX IF EXISTS "idx_user_search_times_user_search_times_group_id";
CREATE INDEX idx_user_search_times_user_search_times_group_id ON user_search_times ("user_search_times_group_id");

DROP TABLE IF EXISTS "user_search_times_groups";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_search_times_groups" (
  "user_search_times_group_id" bigint  NOT NULL,
  "user_id" bigint  NOT NULL,
  "active" boolean  NOT NULL DEFAULT TRUE,
  PRIMARY KEY ("user_search_times_group_id")
);

DROP INDEX IF EXISTS "idx_user_search_times_groups_user_id_active";
CREATE INDEX idx_user_search_times_groups_user_id_active ON user_search_times_groups ("user_id","active");

DROP TABLE IF EXISTS "user_searches";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_searches" (
  "user_search_id" bigserial  NOT NULL,
  "user_search_name" varchar(127) NOT NULL DEFAULT '',
  "user_id" bigint  NOT NULL,
  "user_location_id" bigint  DEFAULT NULL,
  "user_search_times_group_id" bigint  DEFAULT NULL,
  "query" text NOT NULL,
  "notification_type" varchar(31) NOT NULL DEFAULT 'NONE',
  "created_datetime" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "status" VARCHAR(31) NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY ("user_search_id")
);

DROP INDEX IF EXISTS "idx_user_searches_user_id_user_search_times_group_id_user_location_id";
CREATE INDEX idx_user_searches_user_id_user_search_times_group_id_user_location_id ON user_searches ("user_id","user_search_times_group_id","user_location_id");