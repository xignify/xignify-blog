<?php
return array(
	"wandu" => array( "name" => "wandu", "controller" => "Xignify\\Admin\\Controller_Wandu" ),
	"member" => array( "name" => "member", "controller" => "Xignify\\Admin\\Controller_Member" ),
	"blog" => array( "name" => "blog", "controller" => "Xignify\\Admin\\Controller_Blog" )
);

// 여기에 하나 추가하고,
// config/kongs/{name}.php 추가
// library/admin/{name}여기에 하나 추가
// src/Xignify/Admin/Controller/{Name} 추가
// src/Xignify/Admin/Model/{Name} 추가
// src/Xignify/Admin/View/{Name} 추가
