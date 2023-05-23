<?php
$messages = 'id int AUTO_INCREMENT,
              name varchar(20),
              email varchar(20),
              message varchar(1000),
              CONSTRAINT id_PK PRIMARY KEY(id)';
$users = 'id int AUTO_INCREMENT,
          name varchar(30),
          phone varchar(20) Unique,
          password varchar(400),
          CONSTRAINT id_pk PRIMARY KEY(id)';
$goods = 'id int AUTO_INCREMENT,
          name varchar(30) UNIQUE,
          selling int(20),
          cost int(20),
          CONSTRAINT id_pk PRIMARY KEY(id)';
$kiosks = 'id int AUTO_INCREMENT,
           name varchar(30) UNIQUE,
           CONSTRAINT id_pk PRIMARY KEY(id)';
$roles = 'user_id int,
 roles varchar(100),
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 PRIMARY KEY(user_id)';

$employees = 'user_id int,
 kiosk_id int,
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY(kiosk_id) REFERENCES kiosks(id) ON DELETE CASCADE,
 PRIMARY KEY(user_id)';

$quantities = 'kiosk_id int,
 good_id int,
 quantity decimal,
 FOREIGN KEY (kiosk_id) REFERENCES kiosks(id) ON DELETE CASCADE,
 FOREIGN KEY (good_id) REFERENCES goods(id) ON DELETE CASCADE,
 CONSTRAINT co_pk PRIMARY KEY(kiosk_id, good_id)';

$stock_takers = 'id int AUTO_INCREMENT,
 user_id int,
 date date,
 PRIMARY KEY(id),
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE';

$accountings = 'id int AUTO_INCREMENT,
 taker_id int,
 sales decimal,
 costs decimal,
 cash decimal,
 variance decimal,
 net_income decimal,
 PRIMARY KEY(id),
 FOREIGN KEY (taker_id) REFERENCES stock_takers(id) ON DELETE CASCADE';

$archive = 'id bigint AUTO_INCREMENT,
 good_id int,
 taker_id int,
 last_quantity decimal,
 current_quantity decimal,
 operation int,
 PRIMARY KEY(id),
 FOREIGN KEY (taker_id) REFERENCES stock_takers(id) ON DELETE CASCADE,
 FOREIGN KEY (good_id) REFERENCES goods(id) ON DELETE CASCADE';

$tables = ['messages' => $messages,
    'users' => $users,
    'roles' => $roles,
    'goods' => $goods,
    'kiosks' => $kiosks,
    'quantities' => $quantities,
    'stock_takers' => $stock_takers,
    'accountings' => $accountings,
    'archive' => $archive,
    'employees' => $employees
];
?>