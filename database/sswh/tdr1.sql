select count(*) as total from (select count(*) from ( select * from auto_check_v3_tickets where (created_at between '2019-12-05 00:00:00' and '2020-02-08 23:59:59') and address_code not in ( 429021,429006,429005,429004,429000,422828,422827,422826,422825,422823,422822,422802,422801,422800,421381,421321,421303,421301,421300,421281,421224,421223,421222,421221,421202,421201,421200,421182,421181,421127,421126,421125,421124,421123,421122,421121,421102,421101,421100,421087,421083,421081,421024,421023,421022,421003,421002,421001,421000,420984,420982,420981,420923,420922,420921,420902,420901,420900,420881,420822,420821,420804,420802,420801,420800,420704,420703,420702,420701,420700,420684,420683,420682,420626,420625,420624,420607,420606,420602,420601,420600,420583,420582,420581,420529,420528,420527,420526,420525,420506,420505,420504,420503,420502,420501,420500,420381,420325,420324,420323,420322,420304,420303,420302,420301,420300,420281,420222,420205,420204,420203,420202,420201,420200,420117,420116,420115,420114,420113,420112,420111,420107,420106,420105,420104,420103,420102,420101,420100,420000,-1 ) ) a group by user_id) b;
select count(*) as total from  ( select * from auto_check_v3_tickets where                      (created_at between '2019-12-05 00:00:00' and '2020-02-08 23:59:59') and address_code not in (429021,429006,429005,429004,429000,422828,422827,422826,422825,422823,422822,422802,422801,422800,421381,421321,421303,421301,421300,421281,421224,421223,421222,421221,421202,421201,421200,421182,421181,421127,421126,421125,421124,421123,421122,421121,421102,421101,421100,421087,421083,421081,421024,421023,421022,421003,421002,421001,421000,420984,420982,420981,420923,420922,420921,420902,420901,420900,420881,420822,420821,420804,420802,420801,420800,420704,420703,420702,420701,420700,420684,420683,420682,420626,420625,420624,420607,420606,420602,420601,420600,420583,420582,420581,420529,420528,420527,420526,420525,420506,420505,420504,420503,420502,420501,420500,420381,420325,420324,420323,420322,420304,420303,420302,420301,420300,420281,420222,420205,420204,420203,420202,420201,420200,420117,420116,420115,420114,420113,420112,420111,420107,420106,420105,420104,420103,420102,420101,420100,420000,-1 ) ) a;
select count(*) as total from  ( select * from auto_check_v3_tickets where                      (created_at between '2019-12-05 00:00:00' and '2020-02-08 23:59:59') and address_code not in ( 429021,429006,429005,429004,429000,422828,422827,422826,422825,422823,422822,422802,422801,422800,421381,421321,421303,421301,421300,421281,421224,421223,421222,421221,421202,421201,421200,421182,421181,421127,421126,421125,421124,421123,421122,421121,421102,421101,421100,421087,421083,421081,421024,421023,421022,421003,421002,421001,421000,420984,420982,420981,420923,420922,420921,420902,420901,420900,420881,420822,420821,420804,420802,420801,420800,420704,420703,420702,420701,420700,420684,420683,420682,420626,420625,420624,420607,420606,420602,420601,420600,420583,420582,420581,420529,420528,420527,420526,420525,420506,420505,420504,420503,420502,420501,420500,420381,420325,420324,420323,420322,420304,420303,420302,420301,420300,420281,420222,420205,420204,420203,420202,420201,420200,420117,420116,420115,420114,420113,420112,420111,420107,420106,420105,420104,420103,420102,420101,420100,420000,-1 )  and check_status in (11, 20, 21, 22) ) a;
select count(*) as total from  ( select * from auto_check_v3_tickets where                      (created_at between '2019-12-05 00:00:00' and '2020-02-08 23:59:59') and address_code not in ( 429021,429006,429005,429004,429000,422828,422827,422826,422825,422823,422822,422802,422801,422800,421381,421321,421303,421301,421300,421281,421224,421223,421222,421221,421202,421201,421200,421182,421181,421127,421126,421125,421124,421123,421122,421121,421102,421101,421100,421087,421083,421081,421024,421023,421022,421003,421002,421001,421000,420984,420982,420981,420923,420922,420921,420902,420901,420900,420881,420822,420821,420804,420802,420801,420800,420704,420703,420702,420701,420700,420684,420683,420682,420626,420625,420624,420607,420606,420602,420601,420600,420583,420582,420581,420529,420528,420527,420526,420525,420506,420505,420504,420503,420502,420501,420500,420381,420325,420324,420323,420322,420304,420303,420302,420301,420300,420281,420222,420205,420204,420203,420202,420201,420200,420117,420116,420115,420114,420113,420112,420111,420107,420106,420105,420104,420103,420102,420101,420100,420000,-1 ) and check_status in (21) ) a;
select sum(money) as total from  ( select * from auto_check_v3_tickets where                    (created_at between '2019-12-05 00:00:00' and '2020-02-08 23:59:59') and address_code not in  (429021,429006,429005,429004,429000,422828,422827,422826,422825,422823,422822,422802,422801,422800,421381,421321,421303,421301,421300,421281,421224,421223,421222,421221,421202,421201,421200,421182,421181,421127,421126,421125,421124,421123,421122,421121,421102,421101,421100,421087,421083,421081,421024,421023,421022,421003,421002,421001,421000,420984,420982,420981,420923,420922,420921,420902,420901,420900,420881,420822,420821,420804,420802,420801,420800,420704,420703,420702,420701,420700,420684,420683,420682,420626,420625,420624,420607,420606,420602,420601,420600,420583,420582,420581,420529,420528,420527,420526,420525,420506,420505,420504,420503,420502,420501,420500,420381,420325,420324,420323,420322,420304,420303,420302,420301,420300,420281,420222,420205,420204,420203,420202,420201,420200,420117,420116,420115,420114,420113,420112,420111,420107,420106,420105,420104,420103,420102,420101,420100,420000,-1)  and check_status in (21) ) a;




INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`company`, `city`, `business`, `customer_id`, `customer_name`, `code`) VALUES ('柳州食饮营销部', '河池市', '柳州食饮河池大化CBD', 'B0456268098', '福满多超市', 'A228');
SELECT
*
FROM
	auto_check_gz_tdr_200107_shop
WHERE
	auto_check_gz_tdr_200107_shop.`code` IN (
		'T13253',
        'T13252',
        'T13249',
        'T13240',
        'T13253',
        'T13252',
        'T13249',
        'T13240',
        'T13227',
        'T13228',
        'T13229',
        'T13231',
        'T13232',
        'T13233',
        'T13234',
        'T13235',
        'T13236',
        'T13237',
        'T13238',
        'T13239',
        'T13241',
        'T13248',
        'T11061',
        'T11064',
        'T11088',
        'T11139',
        'T11434'
	)


	--2月27号删除
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7286', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456449122', '友之邻青环店', 'T7368', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7287', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456278628', '友之邻中柬店', 'T7369', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7288', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458007932', '友之邻莱茵花语', 'T7370', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7289', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456713315', '友之邻合作店', 'T7371', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7290', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458008090', '友之邻盛天店', 'T7372', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7291', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458007280', '友之邻新华店', 'T7373', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7292', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458003040', '友之邻园湖店', 'T7374', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7293', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456765077', '友之邻建政店', 'T7375', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7294', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456752538', '友之邻文物苑店', 'T7376', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7295', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456732141', '友之邻滨湖店', 'T7377', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7296', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456730784', '友之邻东葛三店', 'T7378', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7297', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456615520', '友之邻广园二店', 'T7379', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7298', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456542778', '友之邻莱茵湖畔店', 'T7380', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7299', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456524876', '友之邻新竹店', 'T7381', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7300', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456523897', '友之邻广园店', 'T7382', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7301', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456475502', '友之邻苏荷店', 'T7383', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7302', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456475499', '友之邻凤凰岭店', 'T7384', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7303', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456278633', '友之邻朱瑾店', 'T7385', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7304', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456275357', '友之邻北湖店', 'T7386', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7305', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456275305', '友之邻金州店', 'T7387', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7306', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456275304', '友之邻莫黄屋店', 'T7388', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7307', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0455816185', '友之邻双拥店', 'T7389', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7308', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0455816184', '友之邻平湖店', 'T7390', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7309', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458022040', '友之邻长岗店', 'T7391', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7310', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014181', '友之邻金浦店', 'T7392', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7311', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458008620', '友之邻秀安店', 'T7393', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7312', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013945', '今天荣和店', 'T7394', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7313', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013992', '今天天健店', 'T7395', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7314', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013512', '今天便利店财经店', 'T7396', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7315', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458016599', '今天国际店', 'T7397', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7316', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013534', '今天秀灵店', 'T7398', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7317', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458008587', '今天东葛二店', 'T7399', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7318', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B0456275303', '今天艺术学院', 'T7400', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7319', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458021560', '今天便利店桂雅店', 'T7401', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7320', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458019363', '今天人才市场', 'T7402', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7321', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458017747', '今天西湖店', 'T7403', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7322', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458017606', '今天隆源国际店', 'T7404', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7323', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458017326', '今天五象绿地大厦店', 'T7405', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7324', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458017313', '今天五象万科', 'T7406', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7325', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458017236', '今天五象新区市民中心店', 'T7407', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7326', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458017081', '今天汇春店', 'T7408', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7327', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458016986', '今天半岛康城店', 'T7409', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7328', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458016964', '今天半岛融园店', 'T7410', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7329', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458016620', '今天汇东国际店', 'T7411', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7330', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014504', '金达花园今天', 'T7412', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7331', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014459', '今天万府店', 'T7413', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7332', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014450', '今天莱茵鹭湖店', 'T7414', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7333', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014418', '今天骋望丽都店', 'T7415', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7334', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014393', '今天水岸华府店', 'T7416', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7335', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014230', '今天民主店', 'T7417', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7336', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014211', '今天绿地店', 'T7418', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7337', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014201', '今天宾湖店', 'T7419', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7338', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014190', '今天丰泽店', 'T7420', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7339', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014185', '今天金浦店', 'T7421', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7340', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014175', '今天丽景豪庭店', 'T7422', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7341', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014171', '今天便利店(国贸中心店)', 'T7423', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7342', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014168', '今天南湖名都店', 'T7424', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7343', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014048', '今天桂春店', 'T7425', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7344', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458014035', '今天东方商务港店', 'T7426', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7345', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013739', '今天新兴苑店', 'T7427', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7346', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013642', '今天五象新区大唐店', 'T7428', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7347', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013525', '今天保利店', 'T7429', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7348', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013518', '今天三祺广场店', 'T7430', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7349', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013382', '今天盛天公馆店', 'T7431', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7350', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013352', '今天财富国际', 'T7432', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7351', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013280', '今天万象城', 'T7433', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7352', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013215', '今天现代国际店', 'T7434', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7353', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458013030', '今天便利店风翔', 'T7435', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7354', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458008760', '今天古城店', 'T7436', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7355', '南宁食饮营销部', '南宁市', '南宁食饮南宁商场营销所', 'B6458008588', '今天联发臻品店', 'T7437', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7419', '南宁食饮营销部', '南宁市', '南宁食饮南宁特学营销所', 'B0450436799', '宁铁驿站', 'T7501', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7420', '南宁食饮营销部', '南宁市', '南宁食饮南宁特学营销所', 'B6458013716', '优之道超市', 'T7502', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7422', '南宁食饮营销部', '南宁市', '南宁食饮南宁特学营销所', 'B6458017736', '部队超市', 'T7504', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7423', '南宁食饮营销部', '南宁市', '南宁食饮南宁特学营销所', 'B0450375185', '弘品综合超市（清洵便利店）', 'T7505', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7424', '南宁食饮营销部', '南宁市', '南宁食饮南宁特学营销所', 'B6458004268', '世纪华联超市苏卢店', 'T7506', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7426', '南宁食饮营销部', '南宁市', '南宁食饮南宁特学营销所', 'B6458010160', '无忧乐嘉超市', 'T7508', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7427', '南宁食饮营销部', '南宁市', '南宁食饮南宁特学营销所', 'B6458004797', '利让隆超市', 'T7509', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7428', '南宁食饮营销部', '南宁市', '南宁食饮南宁特学营销所', 'B0456366708', '龙宝生活超市', 'T7510', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7429', '南宁食饮营销部', '南宁市', '南宁食饮南宁特学营销所', 'B0450917137', '百特便利店官塘店', 'T7511', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7430', '南宁食饮营销部', '南宁市', '南宁食饮南宁特学营销所', 'B0450229175', '工联购物中心', 'T7512', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7437', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B6458022322', '好优多购物中心', 'T7519', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7438', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B0450601643', '腾翔佳惠多超市', 'T7520', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7439', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B0455861067', '君浩百货', 'T7521', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7440', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B6458023652', '浩家便利店', 'T7522', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7441', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B6458011132', '润旺超市', 'T7523', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7442', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B0456834679', '有家日杂店', 'T7524', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7443', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B0450843491', '泽众超市（和平街）', 'T7525', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7444', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B0450346063', '利华隆超市东鸣店', 'T7526', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7445', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B0450321091', '新西洋（鸿景店）', 'T7527', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7446', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B0450312428', '澳玛商业广场', 'T7528', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7447', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B6458017755', '万家惠', 'T7529', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7448', '南宁食饮营销部', '南宁市', '南宁食饮武鸣营销所', 'B6458011152', '惠家百货', 'T7530', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7467', '南宁食饮营销部', '南宁市', '南宁食饮里建营销所', 'B0456245871', '福满家超市', 'T7549', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7472', '南宁食饮营销部', '南宁市', '南宁食饮里建营销所', 'B6458001717', '福满园超市', 'T7554', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7473', '南宁食饮营销部', '南宁市', '南宁食饮里建营销所', 'B0456883137', '家家易购超市', 'T7555', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7474', '南宁食饮营销部', '南宁市', '南宁食饮里建营销所', 'B0456475014', '惠一丰生活超市', 'T7556', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7475', '南宁食饮营销部', '南宁市', '南宁食饮里建营销所', 'B0456435983', '鑫满百货超市', 'T7557', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7476', '南宁食饮营销部', '南宁市', '南宁食饮里建营销所', 'B0450729877', '凯鑫生活超市', 'T7558', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7477', '南宁食饮营销部', '南宁市', '南宁食饮里建营销所', 'B6458031886', '惠选校园超市', 'T7559', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7480', '南宁食饮营销部', '南宁市', '南宁食饮里建营销所', 'B6458011588', '欧购生活超市', 'T7562', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7489', '南宁食饮营销部', '凌云县', '南宁食饮凌云CBD', 'B0450229784', '华联超市', 'T7571', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7490', '南宁食饮营销部', '乐业县', '南宁食饮凌云CBD', 'B0450159772', '广百家超市', 'T7572', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7491', '南宁食饮营销部', '凌云县', '南宁食饮凌云CBD', 'B0450031353', '百汇超市', 'T7573', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7492', '南宁食饮营销部', '乐业县', '南宁食饮凌云CBD', 'B0455547785', '好旺家购物广场', 'T7574', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7493', '南宁食饮营销部', '乐业县', '南宁食饮凌云CBD', 'B0450371844', '好旺家旺超市二分店', 'T7575', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7494', '南宁食饮营销部', '凌云县', '南宁食饮凌云CBD', 'B0450037451', '万佳达超市', 'T7576', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7495', '南宁食饮营销部', '乐业县', '南宁食饮凌云CBD', 'B6458010258', '四季购物中心', 'T7577', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7496', '南宁食饮营销部', '西林县', '南宁食饮隆林CBD', 'B0456332457', '惠家购货广场', 'T7578', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7497', '南宁食饮营销部', '西林县', '南宁食饮隆林CBD', 'B0450736122', '新兴超市', 'T7579', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7498', '南宁食饮营销部', '西林县', '南宁食饮隆林CBD', 'B0450654365', '时代润发超市', 'T7580', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7499', '南宁食饮营销部', '西林县', '南宁食饮隆林CBD', 'B6458038656', '盛盛百汇超市', 'T7581', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7500', '南宁食饮营销部', '西林县', '南宁食饮隆林CBD', 'B6458030956', '古障利客隆超市', 'T7582', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7501', '南宁食饮营销部', '西林县', '南宁食饮隆林CBD', 'B6458030441', '方客宏超市', 'T7583', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7502', '南宁食饮营销部', '西林县', '南宁食饮隆林CBD', 'B6458029764', '那劳仟家惠超市', 'T7584', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7503', '南宁食饮营销部', '西林县', '南宁食饮隆林CBD', 'B6458028456', '华联超市', 'T7585', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7504', '南宁食饮营销部', '西林县', '南宁食饮隆林CBD', 'B6458018720', '莉伊生活超市', 'T7586', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7506', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0456706194', '宏耀百汇', 'T7588', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7507', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B6458000916', '平果永盛百货门面', 'T7589', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7508', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0451737729', '中兴百货', 'T7590', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7509', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0450569525', '中兴生活超市', 'T7591', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7510', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B6458018399', '星港超市', 'T7592', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7511', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0450052465', '平果高级中学', 'T7593', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7512', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B6458006182', '广豪世邦超市', 'T7594', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7513', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0456762599', '步步昇超市', 'T7595', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7514', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0456698209', '师生缘生活连锁超市', 'T7596', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7515', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0456625048', '中兴生活超市万豪国际店', 'T7597', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7516', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0451799593', '工程学院超市分店', 'T7598', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7517', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0451744391', '佳士得超市幼师店', 'T7599', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7518', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B6458023800', '中兴中环店', 'T7600', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7519', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0450348999', '龙居购物中心', 'T7601', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7520', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B6458022261', '大佳元龙景世家店', 'T7602', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7521', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B6458021723', '百源购物中心旺江店', 'T7603', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7522', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B6458021474', '步步昇超市培贤店', 'T7604', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7523', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0450283406', '工业园便客超市', 'T7605', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7524', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0450240393', '平果三中', 'T7606', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7525', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0450119609', '新安百汇超市', 'T7607', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('7526', '南宁食饮营销部', '平果县', '南宁食饮平果CBD', 'B0450052451', '大佳园青山园', 'T7608', '0', '0', '0', '0');

--老坛2月28
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('3358', '南宁食饮营销部', '宾阳县', '南宁食饮宾阳CBD', 'B0450333484', '佳仕通超市', 'A3358', '0', '0', '0', '0');

SELECT
*
FROM
	auto_check_gz_tdr_200107_shop
WHERE
	auto_check_gz_tdr_200107_shop.`code` IN (
    'A995',
    'A1189',
    'A1136',
    'A997',
    'A1008',
    'A1192',
    'A994',
    'A1017',
    'A1013',
    'A980',
    'A982',
    'A1002',
    'A1025',
    'A1015',
    'A1020',
    'A1004',
    'A1021',
    'A999',
    'A1011',
    'A1012',
    'A1010',
    'A993',
    'A1157',
    'A1168',
    'A1188',
    'A1144',
    'A988',
    'A987',
    'A986',
    'A1119',
    'A1181',
    'A996',
    'A998',
    'A1062',
    'A1085',
    'A1063',
    'A1066',
    'A1068',
    'A1200',
    'A1040',
    'A1035',
    'A1045',
    'A1184',
    'A1075',
    'A1142',
    'A1091',
    'A1123',
    'A1180',
    'A1077',
    'A1174',
    'A1135',
    'A1179',
    'A1134',
    'A992',
    'A1194',
    'A1046',
    'A1097',
    'A1102',
    'A1199',
    'A1048',
    'A1056',
    'A1178',
    'A1009',
    'A1160',
    'A1171',
    'A1043',
    'A1000',
    'A1187',
    'A1122',
    'A1060',
    'A1069',
    'A1126',
    'A1190',
    'A1183',
    'A1099',
    'A1191',
    'A1177',
    'A1133',
    'A1161',
    'A1172',
    'A1088',
    'A1003',
    'A1104',
    'A1050',
    'A1118',
    'A1054',
    'A1071',
    'A1198',
    'A1145',
    'A1032',
    'A1049',
    'A1042',
    'A981',
    'A1081',
    'A983',
    'A1084',
    'A1083',
    'A1070',
    'A1195',
    'A1156',
    'A1167',
    'A1193',
    'A1130',
    'A1158',
    'A1169',
    'A1141',
    'A1175',
    'A1064',
    'A1150',
    'A1047',
    'A1065',
    'A1082',
    'A989',
    'A1152',
    'A1093',
    'A1094',
    'A1185',
    'A1076',
    'A1086',
    'A979',
    'A1196',
    'A1163',
    'A1182',
    'A1176',
    'A1114',
    'A1061',
    'A1112',
    'A1149',
    'A1147',
    'A1140',
    'A1129',
    'A1113',
    'A1186',
    'A1036',
    'A1128',
    'A1078',
    'A1073',
    'A1001',
    'A1052',
    'A1127',
    'A1072',
    'A1132',
    'A1146',
    'A1059',
    'A1096',
    'A1028',
    'A1092',
    'A984',
    'A1080',
    'A1067',
    'A1057',
    'A1055',
    'A1058',
    'A1197',
    'A1139',
    'A1074',
    'A1103',
    'A1016',
    'A1037',
    'A1044',
    'A1143',
    'A1033',
    'A1041',
    'A1038',
    'A1159',
    'A1170',
    'A1154',
    'A1165',
    'A1155',
    'A1166',
    'A1039',
    'A1053',
    'A1051',
    'A1162',
    'A1173',
    'A1079',
    'A1138',
    'A1121',
    'A1007',
    'A1151',
    'A1131',
    'A1148',
    'A1034',
    'A1137',
    'A1125',
    'A991',
    'A990',
    'A1006',
    'A1124',
    'A1005',
    'A1153',
    'A1164',
    'A1090',
    'A1101',
    'A1089',
    'A1098'
	)

	INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1000', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B0456437534', '亿家乐超市', 'A1000', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1001', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B0450675774', '世纪华联', 'A1001', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1002', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B6458025148', '世纪华联柳兰店', 'A1002', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1003', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B0455787672', '福玛特超市', 'A1003', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1004', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B6458024248', '永兴超市', 'A1004', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1005', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B0450097658', '家家乐超市', 'A1005', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1006', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B0450111221', '鼎丰超市', 'A1006', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1007', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B0450198995', '朝阳超市', 'A1007', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1008', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B6458027985', '朗柏生活超市', 'A1008', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1009', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B0456560537', '朝阳超市锦绣店', 'A1009', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1010', '柳州食饮营销部', '河池市', '柳州食饮营销部南丹CBD', 'B6458023794', '丹泉便利店', 'A1010', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1011', '柳州食饮营销部', '河池市', '柳州食饮营销部南丹CBD', 'B6458023932', '要的便利店', 'A1011', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1012', '柳州食饮营销部', '河池市', '柳州食饮营销部南丹CBD', 'B6458023820', '要的便利店', 'A1012', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1013', '柳州食饮营销部', '河池市', '柳州食饮营销部南丹CBD', 'B6458025527', '宇康快购超市', 'A1013', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1015', '柳州食饮营销部', '河池市', '柳州食饮营销部南丹CBD', 'B6458024578', '惠购超市', 'A1015', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1016', '柳州食饮营销部', '河池市', '柳州食饮营销部南丹CBD', 'B0450347046', '上海联华超市', 'A1016', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1017', '柳州食饮营销部', '河池市', '柳州食饮营销部南丹CBD', 'B6458025756', '丹桂生活超市', 'A1017', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1020', '柳州食饮营销部', '河池市', '柳州食饮营销部南丹CBD', 'B6458024457', '四期要的便利店', 'A1020', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1021', '柳州食饮营销部', '河池市', '柳州食饮营销部南丹CBD', 'B6458024227', '书香要的便利店', 'A1021', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1025', '柳州食饮营销部', '河池市', '柳州食饮营销部南丹CBD', 'B6458024762', '华星超市车站店', 'A1025', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1028', '柳州食饮营销部', '河池市', '柳州食饮营销部巴马CBD', 'B0450579411', '润百家超市', 'A1028', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1032', '柳州食饮营销部', '河池市', '柳州食饮营销部巴马CBD', 'B0455571613', '大众超市', 'A1032', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1033', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450309310', '大洋购物广场城北店', 'A1033', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1034', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450131079', '帝王商贸城', 'A1034', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1035', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B6458008083', '斌丽超市二店', 'A1035', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1036', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450744799', '斌丽超市', 'A1036', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1037', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450339074', '广维天安超市', 'A1037', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1038', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450243211', '大洋购物广场城南店', 'A1038', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1039', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450232037', '金马超市', 'A1039', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1040', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B6458008279', '一家人超市城西店', 'A1040', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1041', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450246611', '百佳超市', 'A1041', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1042', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0455571009', '宜鑫超市', 'A1042', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1043', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0456447293', '金马超市炮楼脚店', 'A1043', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1044', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450321369', '嘉尚佳生活超市', 'A1044', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1045', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B6458007368', '京冠超市二店', 'A1045', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1046', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0456922312', '你和我超市', 'A1046', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1047', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0451659171', '大洋购物广场', 'A1047', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1048', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0456789671', '京冠超市', 'A1048', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1049', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0455571018', '惠生活四店', 'A1049', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1050', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0455760305', '好实惠', 'A1050', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1051', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450231874', '龙福商场', 'A1051', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1052', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450660729', '旺旺购物商场', 'A1052', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1053', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450231875', '圣杰超市', 'A1053', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1054', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0455712565', '众乐百货', 'A1054', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1055', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450430218', '屏南好优多超市', 'A1055', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1056', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0456738937', '聚好源市超', 'A1056', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1057', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450445327', '大洋购物广场', 'A1057', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1058', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450426264', '屏南龙福商场', 'A1058', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1059', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450610092', '三合超市', 'A1059', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1060', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0456332418', '好来客超市', 'A1060', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1061', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450947971', '宜州区佳佳润超市', 'A1061', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1062', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B6458015713', '北山镇宜家惠超市', 'A1062', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1063', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B6458015543', '惠丰超市', 'A1063', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1064', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0451739521', '德胜宜鑫超市', 'A1064', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1065', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0451581444', '胜都购物中心', 'A1065', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1066', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B6458010846', '一家人超市（德胜）', 'A1066', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1067', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450510982', '爱心超市', 'A1067', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1068', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B6458010830', '民生超市', 'A1068', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1069', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0456280253', '人人乐超市', 'A1069', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1070', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0451827159', '多益惠超市', 'A1070', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1071', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0455708644', '平平百货', 'A1071', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1072', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450616781', '北牙好润佳超市', 'A1072', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1073', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450712446', '民心超市', 'A1073', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1074', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450396740', '石别鑫源超市', 'A1074', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1075', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B6458003365', '宜百家超市', 'A1075', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1076', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0451460025', '石别大洋购物广场', 'A1076', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1077', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B6458001993', '超洁果蔬店', 'A1077', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1078', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450713276', '拉浪大洋超市', 'A1078', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1079', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450218266', '老唐超市', 'A1079', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1080', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0450519972', '博达超市', 'A1080', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1081', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0452102493', '超越百货', 'A1081', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1082', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0451544298', '新奥超市', 'A1082', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1083', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0451837014', '宜欣超市', 'A1083', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1084', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B0451837015', '怀远鑫源超市', 'A1084', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1085', '柳州食饮营销部', '河池市', '柳州食饮营销部河池宜州营业所', 'B6458015559', '宜鑫超市', 'A1085', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1086', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0451404779', '嘉湘超市', 'A1086', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1088', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0455823728', '万家乐超市', 'A1088', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1089', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0450039258', '大润家超市', 'A1089', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1090', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0450042220', '惠美佳超市', 'A1090', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1091', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B6458002382', '腾凯超市', 'A1091', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1092', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0450577060', '盛唐超市', 'A1092', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1093', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0451512585', '粤美超市', 'A1093', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1094', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0451507751', '都阳家家乐超市', 'A1094', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1096', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0450600312', '佳佳旺超市', 'A1096', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1097', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0456881853', '永诚超市', 'A1097', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1098', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0450038832', '福玛特超市', 'A1098', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1099', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0456169202', '福多乐六也店', 'A1099', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1101', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0450041626', '大丰汇超市', 'A1101', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1102', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0456880986', 'CCOOP掌合便利店', 'A1102', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1103', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0450390889', '喜洋洋超市', 'A1103', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1104', '柳州食饮营销部', '河池市', '柳州食饮营销部河池大化CBD', 'B0455761459', '裕新超市', 'A1104', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1112', '柳州食饮营销部', '河池市', '柳州食饮营销部河池环江CBD', 'B0450883279', '新华超市', 'A1112', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1113', '柳州食饮营销部', '河池市', '柳州食饮营销部河池环江CBD', 'B0450865323', '星港百货', 'A1113', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1114', '柳州食饮营销部', '河池市', '柳州食饮营销部河池环江CBD', 'B0450977949', '万家福超市', 'A1114', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1118', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0455748343', '铭润超市上任店', 'A1118', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1119', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458019757', '广佰汇超市', 'A1119', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1121', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450206797', '奇隆购物广场城东', 'A1121', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1122', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456437329', '桂客生活超市西城', 'A1122', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1123', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458002361', '铭润生活超市职大', 'A1123', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1124', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450097902', '铭润超市朝阳店', 'A1124', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1125', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450124267', '铭润超市富华店', 'A1125', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1126', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456184838', '慧美超市文化广场店', 'A1126', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1127', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450620166', '铭润超市九龙店', 'A1127', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1128', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450725182', '铭润超市电业店', 'A1128', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1129', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450870292', '奇隆连锁超市西环店', 'A1129', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1130', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451790169', '铭润超市河北店', 'A1130', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1131', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450191636', '铭润超市南新店', 'A1131', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1132', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450616275', '慧美超市四季阳光店', 'A1132', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1133', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456064449', '慧美超市职业学院店', 'A1133', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1134', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456987794', '惠文懿家生活超市', 'A1134', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1135', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458000858', '优盛客超市城', 'A1135', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1136', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458028610', '金邻居生鲜超市', 'A1136', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1137', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450128626', '铭润超市中山', 'A1137', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1138', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450211306', '铭润超市东站店', 'A1138', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1139', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450402100', '慧美超市中山店', 'A1139', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1140', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450873906', '铭润超市人和店', 'A1140', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1141', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451777147', '铭润超市铜鼓店', 'A1141', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1142', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458002960', '桂客鲜生金旅', 'A1142', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1143', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450315189', '鑫鑫超市江北店', 'A1143', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1144', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458022339', '大洋超市城源华府店', 'A1144', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1145', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0455627277', '新一嘉超市金泰店', 'A1145', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1146', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450611664', '平凡超市', 'A1146', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1147', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450876718', '铭润超市西站店', 'A1147', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1148', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450135536', '铭润超市南桥店', 'A1148', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1149', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450878973', '快乐购购物广场', 'A1149', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1150', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451688069', '五和购物广场', 'A1150', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1151', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450192695', '宏鑫超市', 'A1151', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1152', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451528633', '佳佳旺超市中山', 'A1152', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1153', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450093954', '幸福家园超市', 'A1153', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1154', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450240323', '佳佳旺超市建材', 'A1154', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1155', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450237042', '慧美超市东方明珠店', 'A1155', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1156', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451815441', '慧美超市东站店', 'A1156', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1157', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458022444', '大洋糖厂坡店', 'A1157', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1158', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451784601', '铭润超市西环店', 'A1158', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1159', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450242324', '河池三和购物中心', 'A1159', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1160', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456554557', '大洋购物广场文体', 'A1160', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1161', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0455990618', '慧美超市状元西苑店', 'A1161', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1162', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450228925', '奇隆购物广场华隆', 'A1162', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1163', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451337604', '一品福超市', 'A1163', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1164', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450093954', '幸福家园超市', 'A1164', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1165', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450240323', '佳佳旺超市建材', 'A1165', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1166', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450237042', '慧美超市东方明珠店', 'A1166', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1167', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451815441', '慧美超市东站店', 'A1167', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1168', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458022444', '大洋糖厂坡店', 'A1168', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1169', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451784601', '铭润超市西环店', 'A1169', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1170', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450242324', '河池三和购物中心', 'A1170', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1171', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456554557', '大洋购物广场文体', 'A1171', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1172', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0455990618', '慧美超市状元西苑店', 'A1172', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1173', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450228925', '奇隆购物广场华隆', 'A1173', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1174', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458001901', '舒美生活超市', 'A1174', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1175', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451769040', '星悦超市', 'A1175', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1176', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451032612', '彬彬便民超市', 'A1176', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1177', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456085931', '百兴超市', 'A1177', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1178', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456729910', '万家超市', 'A1178', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1179', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456995401', '宇康快购超市', 'A1179', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1180', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458002196', '东澳超市', 'A1180', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1181', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458019386', '锦福佳生活超市', 'A1181', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1182', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451032708', '你和我超市', 'A1182', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1183', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456184520', '云芳超市', 'A1183', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1184', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458006845', '幸福超市', 'A1184', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1185', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451507542', '美益添', 'A1185', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1186', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450816139', '众口超市', 'A1186', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1187', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456437330', '你和我超市', 'A1187', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1188', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458022371', '金邻居超市', 'A1188', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1189', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458032281', '美益天2店', 'A1189', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1190', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456184524', '大丰收超市', 'A1190', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1191', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456086404', '大学士超市', 'A1191', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1192', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458027919', '源莱便利店', 'A1192', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1193', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451799224', '金柳超市', 'A1193', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1194', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456940402', '万家超市江北店', 'A1194', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1195', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451818335', '万家福超市', 'A1195', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1196', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0451337663', '铭润超市老河池店', 'A1196', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1197', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0450423630', '慧美超市东江店', 'A1197', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1198', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0455664979', '和嘉兴百货', 'A1198', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1199', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B0456803531', '保平好优多超市', 'A1199', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('1200', '柳州食饮营销部', '河池市', '柳州食饮营销部河池金城江营业所', 'B6458010516', '益加宜超市', 'A1200', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('979', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B0451340010', '百汇超市', 'A979', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('980', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B6458025416', '壹家超市二店', 'A980', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('981', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B0455067059', '惠生活', 'A981', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('982', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B6458025393', '万家福超市', 'A982', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('983', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B0451837965', '好润佳连锁超市', 'A983', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('984', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B0450535468', '好润佳超市', 'A984', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('986', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B6458022139', '铭海佳超市', 'A986', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('987', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B6458022164', '好优多超市（百旺店）', 'A987', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('988', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B6458022177', '乐家家超市', 'A988', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('989', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B0451539128', '新西洋购物中心', 'A989', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('990', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B0450118164', '宜佳超市', 'A990', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('991', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B0450118167', '向阳超市', 'A991', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('992', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B0456977982', '万家福购物广场（地苏店）', 'A992', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('993', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B6458022899', '万家福购物中心', 'A993', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('994', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B6458026036', '万家福购物广场中学点', 'A994', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('995', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B6458034657', '下坳好优多超市', 'A995', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('996', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B6458018291', '百大超市', 'A996', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('997', '柳州食饮营销部', '河池市', '柳州食饮营销部河池都安CBD', 'B6458028246', '高岭广购超市', 'A997', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_v3_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('999', '柳州食饮营销部', '河池市', '柳州食饮营销部河池东兰CBD', 'B6458024049', '天天鲜超市', 'A999', '0', '0', '0', '0');


SELECT
*
FROM
	auto_check_gz_tdr_200107_shop
WHERE
	auto_check_gz_tdr_200107_shop.`code` IN (
	'T4568',
    'T4602',
    'T4544',
    'T4543',
    'T4549',
    'T4518',
    'T4529',
    'T4575',
    'T4578',
    'T4614',
    'T4610',
    'T4648'
	)
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4436', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B6448035544', '丽凤百货', 'T4518', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4447', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B0440579655', '家乐通', 'T4529', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4461', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B6448101972', '新港超市', 'T4543', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4462', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B0445825455', '佳品惠生活超市', 'T4544', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4467', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B0441596780', '富源隆', 'T4549', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4486', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B0441419186', '滨海娜娜超市', 'T4568', '3', '2', '2', '232');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4493', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B0446091605', '易站便利店', 'T4575', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4496', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B0445891831', '顺鸿百货', 'T4578', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4520', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B0441489982', '玺邻生活超市', 'T4602', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4528', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B0440539179', '永兴生活超市', 'T4610', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4532', '深圳食品营销部', '深圳', '深圳食品横岗终端所', 'B0446460087', '家乐通', 'T4614', '0', '0', '0', '0');
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop` (`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES ('4566', '深圳食品营销部', '深圳', '深圳食品龙岗商场所', 'B0440517386', '坑梓宏富百货', 'T4648', '0', '0', '0', '0');


SELECT
*
FROM
	auto_check_gz_tdr_200107_shop
WHERE
	auto_check_gz_tdr_200107_shop.`code` IN (
'T12203',
'T12206',
'T12208',
'T12210',
'T12211',
'T12212',
'T12213',
'T12216',
'T12217',
'T12218',
'T12224',
'T12226',
'T12227',
'T12228',
'T12229',
'T12230',
'T12232',
'T12235',
'T12236',
'T12237',
'T12238',
'T12239',
'T12242',
'T12244',
'T12251',
'T12253',
'T12245',
'T12246',
'T12248',
'T12249',
'T12255',
'T12258',
'T12260',
'T12263',
'T12267',
'T12269',
'T12270',
'T12272',
'T12275',
'T12276',
'T12281',
'T12285',
'T12286',
'T12288',
'T12289',
'T12292',
'T12296',
'T12301',
'T12302',
'T12304',
'T12370',
'T12372',
'T12373',
'T12308',
'T12309',
'T12310',
'T12312',
'T12313',
'T12314',
'T12315',
'T12317',
'T12318',
'T12320',
'T12321',
'T12322',
'T12323',
'T12324',
'T12325',
'T12326',
'T12327',
'T12328',
'T12329',
'T12330',
'T12331',
'T12332',
'T12333',
'T12334',
'T12335',
'T12336',
'T12337',
'T12340',
'T12341',
'T12342',
'T12343',
'T12345',
'T12346',
'T12347',
'T12349',
'T12351',
'T12353',
'T12354',
'T12355',
'T12356',
'T12357',
'T12358',
'T12360',
'T12361',
'T12362',
'T12364',
'T12366',
'T12367',
'T12368',
'T12369',
'T12374',
'T12395',
'T12398',
'T12400',
'T12405',
'T12406',
'T12415',
'T12423',
'T12408',
'T12409',
'T12410',
'T12411',
'T12412',
'T12413',
'T12414',
'T12417',
'T12419',
'T12420',
'T12421',
'T12427',
'T12434',
'T12437',
'T12443',
'T12452',
'T12454',
'T12456',
'T12457',
'T12458',
'T12459',
'T12461',
'T12462',
'T12463',
'T12465',
'T12466',
'T12467',
'T12470',
'T12471',
'T12472',
'T12474',
'T12475',
'T12476',
'T12477',
'T12478',
'T12479',
'T12480',
'T12481',
'T12483',
'T12484',
'T12485',
'T12487',
'T12488',
'T12490',
'T12491',
'T12492',
'T12493',
'T12494',
'T12495',
'T12496',
'T12498',
'T12499',
'T12500',
'T12504',
'T12511',
'T12513',
'T12514',
'T12516',
'T12517',
'T12518',
'T12523',
'T12525',
'T12526',
'T12527',
'T12529',
'T12530',
'T12531',
'T12533',
'T12535',
'T12536',
'T12539',
'T12542',
'T12543',
'T12545',
'T12548',
'T12550'
	)


SELECT
*
FROM
	auto_check_gz_v3_shop
WHERE
	auto_check_gz_v3_shop.`code` IN (
'A9671',
'A9662',
'A9663',
'A9665',
'A9668',
'A9669',
'A9670',
'A9676',
'A9678',
'A9680',
'A9681',
'A9684',
'A9687',
'A9688',
'A9689',
'A9690',
'A9691',
'A9694',
'A9696',
'A9704',
'A9697',
'A9700',
'A9701',
'A9702',
'A9706',
'A9707',
'A9708',
'A9712',
'A9718',
'A9731',
'A9721',
'A9722',
'A9724',
'A9727',
'A9728',
'A9733',
'A9737',
'A9738',
'A9740',
'A9741',
'A9746',
'A9751',
'A9754',
'A9755',
'A9760',
'A9761',
'A9762',
'A9764',
'A9765',
'A9766',
'A9767',
'A9768',
'A9769',
'A9770',
'A9772',
'A9775',
'A9776',
'A9777',
'A9778',
'A9779',
'A9780',
'A9784',
'A9786',
'A9787',
'A9793',
'A9794',
'A9795',
'A9797',
'A9799',
'A9803',
'A9805',
'A9806',
'A9807',
'A9808',
'A9809',
'A9810',
'A9812',
'A9813',
'A9814',
'A9816',
'A9818',
'A9819',
'A9820',
'A9821',
'A9822',
'A9824',
'A9834',
'A9843',
'A9847',
'A9852',
'A9854',
'A9856',
'A9857',
'A9858',
'A9874',
'A9860',
'A9862',
'A9864',
'A9865',
'A9866',
'A9868',
'A9869',
'A9872',
'A9881',
'A9894',
'A9904',
'A9906',
'A9889',
'A9895',
'A9912',
'A9908',
'A9909',
'A9910',
'A9911',
'A9913',
'A9914',
'A9915',
'A9917',
'A9918',
'A9919',
'A9922',
'A9924',
'A9927',
'A9929',
'A9930',
'A9932',
'A9936',
'A9940',
'A9941',
'A9942',
'A9944',
'A9947',
'A9949',
'A9950',
'A9961',
'A9951',
'A9952',
'A9956',
'A9962',
'A9963',
'A9965',
'A9966',
'A9970',
'A9977',
'A9978',
'A9979',
'A9981',
'A9982',
'A9991',
'A9994',
'A9995',
'A9997',
'A10000',
'A10002'
)


UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2890', `company`='泉州食饮营销部', `city`='泉州市', `business`='泉州所', `customer_id`='B0356566765', `customer_name`='徕缤便利店', `code`='A2890', `upload_num`='5', `pass_num`='5', `success_redpack_num`='5', `send_money`='740' WHERE (`id`='2890');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2898', `company`='泉州食饮营销部', `city`='泉州市', `business`='泉州所', `customer_id`='B0350500784', `customer_name`='家盛超市', `code`='A2898', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2898');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2901', `company`='泉州食饮营销部', `city`='泉州市', `business`='泉州所', `customer_id`='B0350165896', `customer_name`='新志便利店', `code`='A2901', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2901');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2905', `company`='泉州食饮营销部', `city`='泉州市', `business`='泉州所', `customer_id`='B0350773689', `customer_name`='金丰超市', `code`='A2905', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2905');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2915', `company`='泉州食饮营销部', `city`='泉州市', `business`='泉州所', `customer_id`='B0356310386', `customer_name`='美忠超市', `code`='A2915', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2915');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2921', `company`='泉州食饮营销部', `city`='泉州市', `business`='泉州所', `customer_id`='B6358000419', `customer_name`='见福便利店（师院后门）', `code`='A2921', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2921');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2922', `company`='泉州食饮营销部', `city`='泉州市', `business`='泉州所', `customer_id`='B6358005963', `customer_name`='顶呱呱水果', `code`='A2922', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2922');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2926', `company`='泉州食饮营销部', `city`='泉州市', `business`='泉州所', `customer_id`='B6358020031', `customer_name`='菜鸟驿站', `code`='A2926', `upload_num`='2', `pass_num`='2', `success_redpack_num`='2', `send_money`='176' WHERE (`id`='2926');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2936', `company`='泉州食饮营销部', `city`='泉州市', `business`='泉州所', `customer_id`='B0355685797', `customer_name`='曹操到便利店', `code`='A2936', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2936');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2948', `company`='泉州食饮营销部', `city`='泉州市', `business`='鲤城所', `customer_id`='B6358016389', `customer_name`='百姓超市万达店', `code`='A2948', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2948');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2952', `company`='泉州食饮营销部', `city`='泉州市', `business`='鲤城所', `customer_id`='B6358016223', `customer_name`='百姓西环店', `code`='A2952', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2952');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2953', `company`='泉州食饮营销部', `city`='泉州市', `business`='鲤城所', `customer_id`='B6358016127', `customer_name`='百姓超市祥远店云谷三十一家店', `code`='A2953', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2953');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2983', `company`='泉州食饮营销部', `city`='泉州市', `business`='洛江所', `customer_id`='B0356322941', `customer_name`='微广播便利店', `code`='A2983', `upload_num`='2', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2983');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='2986', `company`='泉州食饮营销部', `city`='泉州市', `business`='洛江所', `customer_id`='B0351552010', `customer_name`='新永惠超市', `code`='A2986', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='2986');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3003', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358010399', `customer_name`='优购便利店', `code`='A3003', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3003');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3004', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358001301', `customer_name`='主坚便利店', `code`='A3004', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3004');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3006', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358006823', `customer_name`='旺辉生活超市', `code`='A3006', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3006');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3007', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0351349738', `customer_name`='乐购', `code`='A3007', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3007');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3008', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358003487', `customer_name`='旺旺生鲜超市', `code`='A3008', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3008');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3010', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0351393105', `customer_name`='旺辉便利', `code`='A3010', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3010');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3012', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0356028376', `customer_name`='振恩超市', `code`='A3012', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3012');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3014', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0351329369', `customer_name`='蓉芳超市', `code`='A3014', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3014');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3015', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0351415078', `customer_name`='天猫小店（喜临门）', `code`='A3015', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3015');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3016', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358017452', `customer_name`='珠源便利店', `code`='A3016', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3016');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3017', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0357003869', `customer_name`='优捷', `code`='A3017', `upload_num`='1', `pass_num`='1', `success_redpack_num`='1', `send_money`='88' WHERE (`id`='3017');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3019', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0352136483', `customer_name`='乐购', `code`='A3019', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3019');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3020', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358011381', `customer_name`='友情烟酒行', `code`='A3020', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3020');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3021', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0351470986', `customer_name`='泊灵便利店', `code`='A3021', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3021');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3022', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358017063', `customer_name`='郎盛便利店', `code`='A3022', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3022');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3023', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358002218', `customer_name`='金谱', `code`='A3023', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3023');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3025', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0351354449', `customer_name`='聚源便利', `code`='A3025', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3025');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3026', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358015712', `customer_name`='吉宏超市', `code`='A3026', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3026');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3027', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358015659', `customer_name`='美一天', `code`='A3027', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3027');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3028', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358015604', `customer_name`='爱家便利', `code`='A3028', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3028');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3029', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358015600', `customer_name`='胖子便利', `code`='A3029', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3029');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3031', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358010862', `customer_name`='君彬便利', `code`='A3031', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3031');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3032', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358010656', `customer_name`='東森烟酒行', `code`='A3032', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3032');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3033', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358010404', `customer_name`='旺鑫隆便利店', `code`='A3033', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3033');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3034', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358010387', `customer_name`='阿九便利店', `code`='A3034', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3034');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3035', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358010345', `customer_name`='万兴便利店', `code`='A3035', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3035');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3036', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B6358008305', `customer_name`='兴隆便利', `code`='A3036', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3036');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3038', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0356654357', `customer_name`='源协丰', `code`='A3038', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3038');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3039', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0356076160', `customer_name`='金汇超市', `code`='A3039', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3039');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3040', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0356076159', `customer_name`='利友超市', `code`='A3040', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3040');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3041', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0356076157', `customer_name`='联友', `code`='A3041', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3041');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3042', `company`='泉州食饮营销部', `city`='泉州市', `business`='石狮所', `customer_id`='B0356015167', `customer_name`='利友超市', `code`='A3042', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3042');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3044', `company`='泉州食饮营销部', `city`='泉州市', `business`='锦尚所', `customer_id`='B0350842154', `customer_name`='昇池超市湖西店', `code`='A3044', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3044');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3048', `company`='泉州食饮营销部', `city`='泉州市', `business`='锦尚所', `customer_id`='B0350196437', `customer_name`='235超市', `code`='A3048', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3048');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3057', `company`='泉州食饮营销部', `city`='泉州市', `business`='锦尚所', `customer_id`='B0351431989', `customer_name`='来的福', `code`='A3057', `upload_num`='8', `pass_num`='8', `success_redpack_num`='8', `send_money`='1604' WHERE (`id`='3057');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3059', `company`='泉州食饮营销部', `city`='泉州市', `business`='锦尚所', `customer_id`='B0350184104', `customer_name`='侨兴批发', `code`='A3059', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3059');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3065', `company`='泉州食饮营销部', `city`='泉州市', `business`='锦尚所', `customer_id`='B0351505829', `customer_name`='豪龙超市（石湖店）', `code`='A3065', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3065');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3067', `company`='泉州食饮营销部', `city`='泉州市', `business`='锦尚所', `customer_id`='B0350419659', `customer_name`='朋迎超市(坑东', `code`='A3067', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3067');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3068', `company`='泉州食饮营销部', `city`='泉州市', `business`='锦尚所', `customer_id`='B0351293723', `customer_name`='福万家超市', `code`='A3068', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3068');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3076', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0356461604', `customer_name`='辉华超市', `code`='A3076', `upload_num`='4', `pass_num`='4', `success_redpack_num`='3', `send_money`='464' WHERE (`id`='3076');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3077', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0351478168', `customer_name`='永新食杂', `code`='A3077', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3077');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3079', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B6358013061', `customer_name`='南安市霞美镇盛润部市', `code`='A3079', `upload_num`='2', `pass_num`='2', `success_redpack_num`='2', `send_money`='376' WHERE (`id`='3079');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3086', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0351398592', `customer_name`='二期便利', `code`='A3086', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3086');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3087', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0355777321', `customer_name`='翔龙服务中心', `code`='A3087', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3087');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3090', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0356404509', `customer_name`='燕如便利', `code`='A3090', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3090');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3091', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0350542240', `customer_name`='九牧服务中心', `code`='A3091', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3091');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3093', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0356262630', `customer_name`='恋家食杂店', `code`='A3093', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3093');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3094', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0350125180', `customer_name`='鑫源超市', `code`='A3094', `upload_num`='1', `pass_num`='1', `success_redpack_num`='1', `send_money`='288' WHERE (`id`='3094');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3095', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0350435349', `customer_name`='万盛超市', `code`='A3095', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3095');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3096', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0350114511', `customer_name`='永盛', `code`='A3096', `upload_num`='1', `pass_num`='1', `success_redpack_num`='1', `send_money`='288' WHERE (`id`='3096');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3097', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0351244689', `customer_name`='闽盛超市', `code`='A3097', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3097');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3098', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0351394345', `customer_name`='季季乐超市', `code`='A3098', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3098');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3099', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0351431200', `customer_name`='李西美乐超市', `code`='A3099', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3099');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3100', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0356414929', `customer_name`='森馨便利店', `code`='A3100', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3100');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3104', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0356059959', `customer_name`='省心生鲜购物', `code`='A3104', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3104');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3105', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0351385178', `customer_name`='客客旺超市', `code`='A3105', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3105');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3106', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B0350040154', `customer_name`='鑫源超市', `code`='A3106', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3106');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3107', `company`='泉州食饮营销部', `city`='南安', `business`='南安所', `customer_id`='B6358019510', `customer_name`='渡船角', `code`='A3107', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3107');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3112', `company`='泉州食饮营销部', `city`='泉州市', `business`='洪濑所', `customer_id`='B0350146893', `customer_name`='新天地超市', `code`='A3112', `upload_num`='28', `pass_num`='22', `success_redpack_num`='22', `send_money`='4736' WHERE (`id`='3112');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3113', `company`='泉州食饮营销部', `city`='泉州市', `business`='洪濑所', `customer_id`='B6358028949', `customer_name`='欢乐购', `code`='A3113', `upload_num`='30', `pass_num`='17', `success_redpack_num`='17', `send_money`='4496' WHERE (`id`='3113');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3114', `company`='泉州食饮营销部', `city`='泉州市', `business`='洪濑所', `customer_id`='B0355260345', `customer_name`='如意超市', `code`='A3114', `upload_num`='2', `pass_num`='2', `success_redpack_num`='2', `send_money`='476' WHERE (`id`='3114');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3118', `company`='泉州食饮营销部', `city`='泉州市', `business`='洪濑所', `customer_id`='B0350192115', `customer_name`='美盛超市', `code`='A3118', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3118');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3119', `company`='泉州食饮营销部', `city`='泉州市', `business`='洪濑所', `customer_id`='B0355639181', `customer_name`='鑫盛食杂', `code`='A3119', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3119');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3120', `company`='泉州食饮营销部', `city`='泉州市', `business`='洪濑所', `customer_id`='B6358008358', `customer_name`='同一家超市', `code`='A3120', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3120');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3123', `company`='泉州食饮营销部', `city`='泉州市', `business`='洪濑所', `customer_id`='B0351558281', `customer_name`='福星隆', `code`='A3123', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3123');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3132', `company`='泉州食饮营销部', `city`='泉州市', `business`='洪濑所', `customer_id`='B0353163116', `customer_name`='佳友超市（西林店）', `code`='A3132', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3132');
UPDATE `33wh`.`auto_check_gz_v3_shop` SET `id`='3160', `company`='泉州食饮营销部', `city`='泉州市', `business`='水头所', `customer_id`='B0351331118', `customer_name`='家家福超市', `code`='A3160', `upload_num`='0', `pass_num`='0', `success_redpack_num`='0', `send_money`='0' WHERE (`id`='3160');


INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12121, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441584628', '家家乐百货', 'T12203', 1, 1, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12124, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440127312', '鑫鸿福超市', 'T12206', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12126, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445762869', '富家欢超市', 'T12208', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12128, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0447314981', '盛世华联', 'T12210', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12129, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441782880', '超群百货', 'T12211', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12130, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446182503', '富家欢', 'T12212', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12131, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441763749', '乐家生活超市', 'T12213', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12134, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441450866', '乐惠多生活超市', 'T12216', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12135, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441494107', '陆佳一', 'T12217', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12136, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448081595', '好又多', 'T12218', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12142, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446296539', '美家惠康', 'T12224', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12144, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446165178', '汇源超市', 'T12226', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12145, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445717494', '乐惠多百货', 'T12227', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12146, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448048494', '盛世华联', 'T12228', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12147, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441452391', '富华双佳', 'T12229', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12148, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448035762', '万胜佳', 'T12230', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12150, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445938028', '优购超市', 'T12232', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12153, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441451528', '华城生活超市', 'T12235', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12154, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441626960', '万家富', 'T12236', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12155, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441782876', '金东源百货', 'T12237', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12156, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448040258', '宜佳超市', 'T12238', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12157, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440612336', '金东源百货', 'T12239', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12160, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441779005', '金百汇生活超市', 'T12242', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12162, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448107642', '万家百汇', 'T12244', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12163, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446955474', '启发百货', 'T12245', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12164, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446165192', '家家福百货', 'T12246', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12166, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445563694', '鑫辉百货', 'T12248', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12169, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441494515', '掌合便利店', 'T12251', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12171, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445830313', '百润超市', 'T12253', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12173, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446945700', '万联优鲜超市', 'T12255', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12176, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441492357', '鑫佳百货', 'T12258', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12178, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440984104', '鑫辉百货', 'T12260', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12181, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441431706', '万顺发', 'T12263', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12185, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445928824', '华宁百货', 'T12267', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12188, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446287217', '美嘉通', 'T12270', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12190, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448055590', '世纪华瑞', 'T12272', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12193, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0447348413', '忆家乐', 'T12275', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12194, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448011437', '乐家嘉', 'T12276', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12199, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446208735', '美佳亲', 'T12281', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12203, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441495496', '华威生活超市', 'T12285', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12204, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448024542', '云兜便利店', 'T12286', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12206, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441468258', '福万家', 'T12288', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12207, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448077801', '家福多超市', 'T12289', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12210, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441425014', '家得福', 'T12292', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12214, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0447348408', '裕峰百货', 'T12296', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12219, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448062763', '金福元', 'T12301', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12220, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446457715', '百汇', 'T12302', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12222, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446165264', '天福', 'T12304', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12226, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446498714', '快乐为民便利', 'T12308', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12227, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448016405', '宇恒乐便利店', 'T12309', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12228, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448025088', '百乐维便利店', 'T12310', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12230, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441439135', '5+1便利店', 'T12312', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12231, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446935030', '家乐宝便利店', 'T12313', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12232, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448046283', '乐家嘉便利店', 'T12314', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12233, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441349039', '旭宜快购便利店', 'T12315', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12235, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448068870', '旭宜快购便利店', 'T12317', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12236, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448017996', '汇宜佳便利店', 'T12318', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12238, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441353163', '陆佳一生活超市', 'T12320', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12239, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448062097', '天客便利店', 'T12321', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12240, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446403505', '吉多汇便利店', 'T12322', 2, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12241, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445991720', '百乐佳便利店', 'T12323', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12242, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448083962', '诚富信生活超市', 'T12324', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12243, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448024495', '百佳盛生活超市', 'T12325', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12244, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446430458', '天福便利店', 'T12326', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12245, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441337540', '家乐通便利店', 'T12327', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12246, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441392985', '365百货', 'T12328', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12247, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441791216', '海福生活超市', 'T12329', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12248, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448022568', '仆公英便利店', 'T12330', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12250, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448034407', '盛世华联生活超市', 'T12332', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12251, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441377935', '鸿福百货', 'T12333', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12252, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446490195', '美惠购生活超市', 'T12334', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12253, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441385889', '鸿福百货', 'T12335', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12254, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441441861', '鑫辉百货', 'T12336', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12255, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441377296', '东海百货', 'T12337', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12258, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448047014', '逛街乐生活精选超市', 'T12340', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12259, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446346878', '天天鲜生活超市', 'T12341', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12260, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441381474', '金百汇生活超市', 'T12342', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12261, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448036841', '7+1便利店', 'T12343', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12263, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448079141', '美惠佳便利店', 'T12345', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12264, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441442845', '范饭鲜生生活超市', 'T12346', 3, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12265, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445571839', '掌合便利店', 'T12347', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12267, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445998909', '天福便利店', 'T12349', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12269, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441381473', '旺福百货', 'T12351', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12271, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445837076', '阿里之门便利店', 'T12353', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12272, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448016496', '万佳美', 'T12354', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12273, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448008257', '每天惠便利店', 'T12355', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12274, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448020557', '想家便利店', 'T12356', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12275, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446021494', '桔子便利店', 'T12357', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12276, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441439375', '家家生活超市', 'T12358', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12278, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445558986', '万佳美', 'T12360', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12279, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441439376', '好方便利店', 'T12361', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12280, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441402396', '阿里巴巴', 'T12362', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12282, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448016196', '维乐佳便利店', 'T12364', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12284, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448078219', '乐享生活空间便利店', 'T12366', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12285, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0447315015', '新福天便利店', 'T12367', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12286, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448058326', '美佳亲便利店', 'T12368', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12287, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446177287', '维客佳', 'T12369', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12288, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448028843', '维客佳便利店', 'T12370', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12290, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441385892', '万乐福生活超市', 'T12372', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12291, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448081858', '友邻乐', 'T12373', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12292, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441369012', '憨豆记便利店', 'T12374', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12313, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441476702', '金百汇（赤岭头店）', 'T12395', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12316, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448008517', '佳乐生活超市', 'T12398', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12318, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448040801', '慕希', 'T12400', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12323, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446165191', '百盛佳连锁超市', 'T12405', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12324, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448040175', '天猫小店（都市微）', 'T12406', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12326, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446469893', '盛世华联', 'T12408', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12327, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440535063', '金乐福生活超市', 'T12409', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12328, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446001190', '华联生活超市', 'T12410', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12329, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448011442', '易诚精彩生活超市', 'T12411', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12330, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445927533', '佳乐生鲜连锁超市', 'T12412', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12331, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448038404', '同得利百货', 'T12413', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12332, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446679015', '景田生活超市', 'T12414', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12333, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441714001', '佳乐生活超市', 'T12415', 2, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12335, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448006349', '乐顺生活超市', 'T12417', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12337, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448034166', '陆佳一生活超市', 'T12419', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12338, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440520002', '鑫众旺', 'T12420', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12339, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445576422', '绿康生活超市', 'T12421', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12341, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440757608', '佳乐生活超市', 'T12423', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12345, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445990392', '益多家', 'T12427', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12352, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446961154', '万海盛', 'T12434', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12355, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446196624', '维客佳', 'T12437', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12361, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445806340', '鑫万鸿', 'T12443', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12370, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448046894', '开心生活超市', 'T12452', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12372, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446019503', '喜洋洋', 'T12454', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12374, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441604631', '鑫辉百货', 'T12456', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12375, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441532503', '中百汇百货', 'T12457', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12376, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441585181', '振兴生活超市', 'T12458', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12377, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448022440', '中业爱民', 'T12459', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12379, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446514445', '中业爱民便利店', 'T12461', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12380, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446509441', '億特佳品', 'T12462', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12381, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446890779', '中业爱民', 'T12463', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12383, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441538346', '家乐通', 'T12465', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12384, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0443160974', '同得利百货', 'T12466', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12385, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446093070', '鑫辉百货', 'T12467', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12388, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441608957', '陆兴百货', 'T12470', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12389, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441604636', '洪隆生活超市', 'T12471', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12390, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441608950', '人人和百货', 'T12472', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12392, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441559564', '爱民便利店', 'T12474', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12393, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445678465', '家和百货', 'T12475', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12394, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446737082', '兴星百货', 'T12476', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12395, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441754040', '天猫小店', 'T12477', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12396, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446436490', '康金源超市', 'T12478', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12397, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441791235', '佳如百货', 'T12479', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12398, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441783303', '志杨利群百货', 'T12480', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12399, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445503735', '隆客多百货', 'T12481', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12401, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441779154', '佳乐生活超市', 'T12483', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12402, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441754011', '美佳生活百货', 'T12484', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12403, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440507375', '喜洋洋', 'T12485', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12405, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445678463', '鑫爱家百货', 'T12487', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12406, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441754039', '佳旺便利店', 'T12488', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12408, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441753968', '壹嘉顺便利', 'T12490', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12409, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446021299', '佳乐生活超市', 'T12491', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12410, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440341438', '佳乐生鲜超巿', 'T12492', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12411, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440708413', '美旺佳', 'T12493', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12412, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441557356', '佳乐生鲜', 'T12494', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12413, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440603167', '金豪超市', 'T12495', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12414, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440604383', '兴昌隆百货', 'T12496', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12416, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445944438', '全福便利店', 'T12498', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12417, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445690246', '万兴百货', 'T12499', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12418, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448011317', '中业爱民', 'T12500', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12422, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446287214', '福家乐生鲜超市', 'T12504', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12429, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446113501', '百惠', 'T12511', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12431, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446279079', '阿里之门', 'T12513', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12432, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446175662', '乡情便利店', 'T12514', 1, 1, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12434, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446222382', '天福', 'T12516', 6, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12435, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440804416', '大家好百货', 'T12517', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12436, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446257114', '微站便利店', 'T12518', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12441, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441520490', '惠家百货', 'T12523', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12443, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441525385', '维客佳', 'T12525', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12444, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446739737', '金百汇生活超市', 'T12526', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12445, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441519035', '益茂平价百货', 'T12527', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12447, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446132757', '鑫辉百货', 'T12529', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12448, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440203636', '万众百货', 'T12530', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12449, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448013344', '顺意生活超市', 'T12531', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12451, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448028508', '万地百货', 'T12533', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12453, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448040308', '瑞华百货', 'T12535', 2, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12454, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446356210', '华明厂士多店', 'T12536', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12457, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441535776', '佳又惠百货', 'T12539', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12460, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440449081', '鑫辉百货', 'T12542', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12461, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441535682', '易站', 'T12543', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12463, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441333193', '鑫辉百货', 'T12545', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12466, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441773418', '维客佳', 'T12548', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_tdr_200107_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (12468, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445944447', '万佳美', 'T12550', 1, 0, 0, 0);



INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19809, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441773418', '维客佳', 'A10000', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19471, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0447314981', '盛世华联', 'A9662', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19474, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441763749', '乐家生活超市', 'A9665', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19477, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441450866', '乐惠多生活超市', 'A9668', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19478, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441494107', '陆佳一', 'A9669', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19479, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448081595', '好又多', 'A9670', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19480, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445105047', '盛世华联超市', 'A9671', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19487, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446165178', '汇源超市', 'A9678', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19489, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448048494', '盛世华联', 'A9680', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19490, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441452391', '富华双佳', 'A9681', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19493, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445938028', '优购超市', 'A9684', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19496, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441451528', '华城生活超市', 'A9687', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19497, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441626960', '万家富', 'A9688', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19498, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441782876', '金东源百货', 'A9689', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19499, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448040258', '宜佳超市', 'A9690', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19500, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440612336', '金东源百货', 'A9691', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19503, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441779005', '金百汇生活超市', 'A9694', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19505, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448107642', '万家百汇', 'A9696', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19509, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445563694', '鑫辉百货', 'A9700', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19510, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448048687', '喜洋洋', 'A9701', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19511, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448069121', '爱宜购', 'A9702', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19513, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448009293', '科尔便利店', 'A9704', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19515, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448029143', '尚鲜连锁超市', 'A9706', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19516, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446945700', '万联优鲜超市', 'A9707', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19517, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448025053', '百润超市', 'A9708', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19521, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440984104', '鑫辉百货', 'A9712', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19527, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446171716', '盛世华联', 'A9718', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19530, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445467064', '乐家嘉', 'A9721', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19531, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446287217', '美嘉通', 'A9722', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19533, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448055590', '世纪华瑞', 'A9724', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19536, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0447348413', '忆家乐', 'A9727', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19537, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448011437', '乐家嘉', 'A9728', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19540, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448056688', '正能佳超市', 'A9731', 1, 1, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19542, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446208735', '美佳亲', 'A9733', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19546, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441495496', '华威生活超市', 'A9737', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19547, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448024542', '云兜便利店', 'A9738', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19549, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441468258', '福万家', 'A9740', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19550, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448077801', '家福多超市', 'A9741', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19555, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446488451', '乐百购', 'A9746', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19560, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445560753', '老百姓', 'A9751', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19563, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446457715', '百汇', 'A9754', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19564, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445460953', '超福', 'A9755', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19569, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446498714', '快乐为民便利', 'A9760', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19570, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448016405', '宇恒乐便利店', 'A9761', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19571, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448025088', '百乐维便利店', 'A9762', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19573, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441439135', '5+1便利店', 'A9764', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19574, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446935030', '家乐宝便利店', 'A9765', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19575, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448046283', '乐家嘉便利店', 'A9766', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19576, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441349039', '旭宜快购便利店', 'A9767', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19577, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441442834', '美家福连锁超市', 'A9768', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19578, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448068870', '旭宜快购便利店', 'A9769', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19579, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448017996', '汇宜佳便利店', 'A9770', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19581, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441353163', '陆佳一生活超市', 'A9772', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19584, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445991720', '百乐佳便利店', 'A9775', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19585, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448083962', '诚富信生活超市', 'A9776', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19586, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448024495', '百佳盛生活超市', 'A9777', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19587, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446430458', '天福便利店', 'A9778', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19588, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441337540', '家乐通便利店', 'A9779', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19589, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441392985', '365百货', 'A9780', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19593, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448034407', '盛世华联生活超市', 'A9784', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19595, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446490195', '美惠购生活超市', 'A9786', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19596, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441385889', '鸿福百货', 'A9787', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19602, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446346878', '天天鲜生活超市', 'A9793', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19603, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441381474', '金百汇生活超市', 'A9794', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19604, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448036841', '7+1便利店', 'A9795', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19606, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448079141', '美惠佳便利店', 'A9797', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19608, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445571839', '掌合便利店', 'A9799', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19612, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441381473', '旺福百货', 'A9803', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19614, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445837076', '阿里之门便利店', 'A9805', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19615, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448016496', '万佳美', 'A9806', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19616, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448008257', '每天惠便利店', 'A9807', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19617, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448020557', '想家便利店', 'A9808', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19618, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446021494', '桔子便利店', 'A9809', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19619, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441439375', '家家生活超市', 'A9810', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19621, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445558986', '万佳美', 'A9812', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19622, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441439376', '好方便利店', 'A9813', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19623, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441402396', '阿里巴巴', 'A9814', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19625, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448016196', '维乐佳便利店', 'A9816', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19627, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448078219', '乐享生活空间便利店', 'A9818', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19628, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0447315015', '新福天便利店', 'A9819', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19629, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448058326', '美佳亲便利店', 'A9820', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19630, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446177287', '维客佳', 'A9821', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19631, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448028843', '维客佳便利店', 'A9822', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19633, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441385892', '万乐福生活超市', 'A9824', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19643, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445786515', '同益生活超市', 'A9834', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19652, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448020616', '天猫小店', 'A9843', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19656, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441476702', '金百汇（赤岭头店）', 'A9847', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19661, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448040801', '慕希', 'A9852', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19663, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441443045', '天猫小店', 'A9854', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19665, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441434731', '福源百货', 'A9856', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19666, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446165191', '百盛佳连锁超市', 'A9857', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19667, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448040175', '天猫小店（都市微）', 'A9858', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19669, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446469893', '盛世华联', 'A9860', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19671, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446001190', '华联生活超市', 'A9862', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19673, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445927533', '佳乐生鲜连锁超市', 'A9864', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19674, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448038404', '同得利百货', 'A9865', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19675, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446679015', '景田生活超市', 'A9866', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19677, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441714016', '百润', 'A9868', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19678, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448006349', '乐顺生活超市', 'A9869', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19681, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440520002', '鑫众旺', 'A9872', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19683, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440757613', '新家乐生活超市', 'A9874', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19690, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446339272', '优鲜美', 'A9881', 2, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19698, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446196624', '维客佳', 'A9889', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19703, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445789919', '好邻居', 'A9894', 2, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19704, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445806340', '鑫万鸿', 'A9895', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19713, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448046894', '开心生活超市', 'A9904', 2, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19715, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446019503', '喜洋洋', 'A9906', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19717, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441604631', '鑫辉百货', 'A9908', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19718, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441532503', '中百汇百货', 'A9909', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19719, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441585181', '振兴生活超市', 'A9910', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19720, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448022440', '中业爱民', 'A9911', 1, 1, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19721, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445725866', '万乐福百货', 'A9912', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19722, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446514445', '中业爱民便利店', 'A9913', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19723, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446509441', '億特佳品', 'A9914', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19724, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446890779', '中业爱民', 'A9915', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19726, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441538346', '家乐通', 'A9917', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19727, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0443160974', '同得利百货', 'A9918', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19728, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446093070', '鑫辉百货', 'A9919', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19731, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441608957', '陆兴百货', 'A9922', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19733, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441608950', '人人和百货', 'A9924', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19736, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445678465', '家和百货', 'A9927', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19738, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441754040', '天猫小店', 'A9929', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19739, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446436490', '康金源超市', 'A9930', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19741, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441783303', '志杨利群百货', 'A9932', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19745, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441754011', '美佳生活百货', 'A9936', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19749, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441754039', '佳旺便利店', 'A9940', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19750, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441557247', '佳乐百货', 'A9941', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19751, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441753968', '壹嘉顺便利', 'A9942', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19753, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440341438', '佳乐生鲜超巿', 'A9944', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19756, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440603167', '金豪超市', 'A9947', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19758, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441779106', '富华百货', 'A9949', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19759, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445944438', '全福便利店', 'A9950', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19760, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0445690246', '万兴百货', 'A9951', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19761, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B6448011317', '中业爱民', 'A9952', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19765, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446287214', '福家乐生鲜超市', 'A9956', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19770, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441494986', '人人和生活超市', 'A9961', 1, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19771, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0447314947', '新惠佳', 'A9962', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19772, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446113501', '百惠', 'A9963', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19774, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446279079', '阿里之门', 'A9965', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19775, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446175662', '乡情便利店', 'A9966', 1, 1, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19779, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446257114', '微站便利店', 'A9970', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19786, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441525385', '维客佳', 'A9977', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19787, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446739737', '金百汇生活超市', 'A9978', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19788, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441519035', '益茂平价百货', 'A9979', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19790, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0446132757', '鑫辉百货', 'A9981', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19791, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440203636', '万众百货', 'A9982', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19800, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441535776', '佳又惠百货', 'A9991', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19803, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0440449081', '鑫辉百货', 'A9994', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19804, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441535682', '易站', 'A9995', 0, 0, 0, 0);
INSERT INTO `33wh`.`auto_check_gz_v3_shop`(`id`, `company`, `city`, `business`, `customer_id`, `customer_name`, `code`, `upload_num`, `pass_num`, `success_redpack_num`, `send_money`) VALUES (19806, '深圳食品营销部', '深圳', '深圳食品龙华终端所', 'B0441333193', '鑫辉百货', 'A9997', 0, 0, 0, 0);



