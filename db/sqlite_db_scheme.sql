CREATE TABLE "users" ("username" VARCHAR PRIMARY KEY  NOT NULL  UNIQUE , "password" VARCHAR NOT NULL , "plugins" VARCHAR, "email" VARCHAR, "books" VARCHAR);
CREATE TABLE "rssinfo" ("webmaster" VARCHAR PRIMARY KEY  NOT NULL  UNIQUE,"bloglink" VARCHAR,"rsslink" VARCHAR,"blogtitle" VARCHAR,"blogdescription" VARCHAR,"generator" VARCHAR,"language" VARCHAR);
CREATE TABLE "blogs" ("post" VARCHAR PRIMARY KEY  NOT NULL ,"date" VARCHAR,"postedBy" VARCHAR,"title" VARCHAR);
CREATE TABLE "books" ("dir" VARCHAR PRIMARY KEY  NOT NULL ,"title" VARCHAR,"description" VARCHAR,"date" VARCHAR,"visible" VARCHAR,"category" VARCHAR,"status" VARCHAR,"epub" VARCHAR,"pdf" VARCHAR,"analyzer" VARCHAR,"modified" VARCHAR,"translations" VARCHAR,"textdirection" VARCHAR);
CREATE TABLE "log" ("username" VARCHAR,"date" VARCHAR,"action" VARCHAR,"ipaddress" VARCHAR);
CREATE INDEX log_date ON log ( date );
CREATE INDEX log_username ON log ( username );
