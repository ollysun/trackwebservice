CREATE TABLE IF NOT EXISTS parcel_edit_history(
  id int(11) not NULL PRIMARY KEY AUTO_INCREMENT,
  before_data TEXT not NULL ,
  after_data TEXT not NULL ,
  changed_by VARCHAR(25) not NULL,
  modified_at DATETIME not NULL
);