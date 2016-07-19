-- phpMyAdmin SQL Dump
-- version 3.4.10.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 10, 2013 at 05:40 AM
-- Server version: 5.1.55
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `seemore_beta`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `last_login_date` datetime NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  `browser_type` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`user_id`, `user_name`, `password`, `group_id`, `email_address`, `phone`, `created_date`, `modified_date`, `last_login_date`, `user_ip`, `browser_type`) VALUES
(1, 'seemore ', '54ed21936615f560c0af2766e5110cec', '1', 'meher.e@digitalimperia.com', '433-434-3434', '0000-00-00 00:00:00', '2013-09-03 04:38:13', '2013-09-03 04:56:22', '183.82.0.112', 'Mozilla Firefox 23.0 on mac'),
(2, 'meher', '4ff72f1d6b3eb4696842d91268481aef', '2', 'meher2020@gmail.com', '222-222-2222', '2013-08-30 17:41:49', '0000-00-00 00:00:00', '2013-09-04 08:41:13', '::1', 'Google Chrome 29.0.1547.65 on mac'),
(3, 'meher', '4ff72f1d6b3eb4696842d91268481aef', '1', 'meher.e@digitalimperia.com', '873-843-434', '2013-09-03 04:55:35', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '183.82.0.112', 'Mozilla Firefox 23.0 on mac');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `addinfo` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `browser` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` mediumtext NOT NULL,
  `location` varchar(255) NOT NULL,
  `featured` varchar(255) NOT NULL,
  `include_about` varchar(255) NOT NULL,
  `excerpt` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  `created_date` datetime NOT NULL,
  `ip` varchar(255) NOT NULL,
  `browser` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `subtitle`, `location`, `featured`, `include_about`, `excerpt`, `content`, `created_date`, `ip`, `browser`) VALUES
(1, 'History of shopping, looking to the future', 'SeeMore Interactive''s infographic explores the evolution of shopping and how technology has impacted the way we shop.', '', '1', '0', '', '<p class="p1"><span class="s1"></span><a href="http://www.seemoreinteractive.com/infographics/" title="SeeMore Interactive''s infographic">SeeMore Interactive''s infographic</a>&nbsp;explores the evolution of shopping&nbsp;and&nbsp;how technology has impacted the way&nbsp;we shop. From strip malls, to&nbsp;catalogs, to the initial eBay ecommerce, and to today''s version of&nbsp;ecommerce including out of the box retailers like Etsy, Warby Parker&nbsp;and Birchbox - this infographic takes a look at how far we''ve&nbsp;come, while giving you a sneak peek at where we''re headed as we&nbsp;experience the resurgence of brick-and-mortar through pop-up shops and&nbsp;emerging, mobile technology.</p>\n<p class="p1"><a href="http://www.seemoreinteractive.com/infographics/"><img alt="Seemore Interactive - Infographics" src="http://seemoreinteractive.com/images/evolution_of_shopping_embed.jpg"></a></p>', '2013-06-11 00:00:00', '', ''),
(2, 'Columbus Startup, SeeMore Interactive, Bolsters Leadership Team In Preparation for Major Retail Push', 'Columbus-based SeeMore Interactive, Inc. has added two experienced startup leaders to its team: Suresh Pillai is the new Chief Technology Officer and Bob Fisher has been elected to the company''s Board of Directors.', '', '1', '1', '<h2>Chief Technology Officer and Board of Directors additions Position SeeMore Interactive for Growth in 2013</h2>', '<p class="p1">Columbus-based SeeMore Interactive, Inc. has added two&nbsp;experienced startup leaders to its team: Suresh Pillai is the new Chief Technology&nbsp;Officer and Bob Fisher has been elected to the company''s Board of Directors.</p><p class="p2"></p><p class="p1">"SeeMore Interactive helps retailers bridge the digital and physical shopping&nbsp;experiences to better serve customers. As we look to scale our product, having&nbsp;experienced, forward-thinking leadership on our team is critical," explained Neal&nbsp;Applefeld, founder and CEO of SeeMore Interactive. "Suresh and Bob bring valuable&nbsp;experiences to the table, as well as the technical know-how needed to build and grow a&nbsp;startup."</p><p class="p2"></p><p class="p1">Pillai will draw on 26 years of technology experience as he focuses on expanding&nbsp;SeeMore Interactive''s image recognition and augmented reality technology. Prior to&nbsp;joining the retail-focused startup, Pillai worked with Verizon Wireless, Cellular One,&nbsp;Airtouch, and GTE.</p><p class="p2"></p><p class="p1">A former member of SeeMore Interactive''s advisory committee, Fisher is a successful&nbsp;entrepreneur with 35 years of executive experience. For more than two decades, he&nbsp;served as president and CEO of Foresight, the technology company he founded, built&nbsp;and eventually sold to TIBCO Software, Inc.</p><p class="p2"></p><p class="p1">&nbsp;"Digital shopping is on the verge of a sea-change brought about by enhanced mobile&nbsp;technologies." says Mr. Fisher. "SeeMore is at the forefront of this revolution and I am&nbsp;delighted to participate as the company redefines the interactive consumer experience."</p><p class="p2"></p><p class="p1"><strong><span style="font-size:medium;font-family:Arial, Helvetica, sans-serif;">About SeeMore Interactive, Inc.</span></strong></p><p class="p1">SeeMore Interactive helps retailers and organizations engage in powerful ways with consumers&nbsp;by creating a new and exciting level of product interaction. SeeMore integrates image&nbsp;recognition, recommendation engine and location-based technologies with augmented reality&nbsp;to turn everything viewed through a consumer''s smartphone or mobile device into a digital,&nbsp;interactive experience. For more information, visit <a href="http://www.seemoreinteractive.com/"><span class="s1">http://www.seemoreinteractive.com</span></a>.</p>', '2013-05-01 00:00:00', '', ''),
(3, 'SeeMore Interactive Names Advisory Committee', 'SeeMore Interactive, Inc., developers of a retail marketing platform that enhances product interaction and consumer engagement, has formed an advisory committee of experts with diverse backgrounds in technology, retail and business sectors.', '', '1', '1', '', '<em>Four technology, business and retail experts will serve as advisors to the company whose imaging software will revolutionize the shopping experience through heightened consumer engagement and interaction.</em><br><br>COLUMBUS, Ohio - October 15, 2012 - SeeMore Interactive, Inc., developers of a retail marketing platform that enhances product interaction and consumer engagement, has formed an advisory committee of experts with diverse backgrounds in technology, retail and business sectors.<br><br>The advisory committee members are Bob Fisher, Rob Goldberg, Rajiv Ramnath and Neil Widerschein.<br><br>"We''ve assembled a committed and deeply experienced team to advise SeeMore on initiatives that will influence our product development and growth strategies moving forward," said Neal Applefeld, president of SeeMore.<br><br>Bob Fisher is a successful entrepreneur with 35 years of executive experience, including 21 years as president and CEO of Foresight, the technology company he founded, built and eventually sold to TIBCO Software, Inc., a publicly held $960 million company. Fisher has raised angel and venture capital during his career. He serves as Entrepreneur in Residence at TechColumbus, an organization based in Columbus, Ohio, that is focused on helping to nurture technology and innovation. Fisher assists start-up firms to become established, acquire funding, design business plans and build management teams.<br><br>Rob Goldberg is an entrepreneur who is general manager of Topps Digital Services, a business unit that was created when he sold the company he founded, GMG Entertainment, to The Topps Company in 2011. Prior to GMG Entertainment, Goldberg was CEO of Gold Marketing Group, an entertainment marketing firm, and spent seven years as an executive growing LAUNCH Media before it was sold to Yahoo! and became Yahoo! Music. Rob''s entrepreneurial spirit began early when at age 22 he was founder and CEO of the llizwe Trading Company, a youth-focused footwear and apparel company in Cape Town, South Africa.<br><br>Dr. Rajiv Ramnath is director of practice at the Collaborative for Enterprise Transformation and Innovation (CETI), Associate Director for the Institute of Sensing Systems, and Associate Professor of Practice in the Department of Computer Science and Engineering at The Ohio State University. He formerly was vice president and chief technology officer at Concentus Technology Corp. and led product-development and government-funded R&amp;D, notably through the National Information Infrastructure Integration Protocols program. He is engaged in developing industry-facing programs of applied R&amp;D, classroom and professional education and technology transfer. His expertise ranges from wireless sensor networking and pervasive computing to business-IT alignment, software engineering and collaborative environments. He teaches software engineering at OSU.<br><br>Neil Widerschein is partner and chief creative officer at SBC Advertising in Columbus, Ohio, a leading agency that works with national and regional retailers to develop campaigns, point-of-sale and numerous other marketing initiatives. In his role with SBC, Widerschein oversees creative approaches and strategies for consumer brands such as SUBWAY, Bed Bath and Beyond, Bob Evans, Elmer''s, Ultimate Software and more.\r\n', '2012-10-05 00:00:00', '', ''),
(4, 'Leading Technology Organization Nominates SeeMore Interactive as Outstanding Startup Business', 'SeeMore Interactive, Inc., developers of a retail product marketing platform designed to enhance product interaction and consumer engagement, has been nominated by TechColumbus for Outstanding Startup Business.', '', '1', '1', '', '<em>The company that is developing imaging software to revolutionize the retail shopping experience has been recognized for its innovation and success.</em><br><br>COLUMBUS, Ohio - September 27, 2012 - SeeMore Interactive, Inc., developers of a retail product marketing platform designed to enhance product interaction and consumer engagement, has been nominated by TechColumbus for Outstanding Startup Business.<br><br>TechColumbus, the catalyst for technology-driven economic growth in the 15 counties that comprise Central Ohio, has recognized SeeMore for developing an innovative product that is commercially available as well as for the company''s potential for continued growth and success.<br><br>"We''re proud to be nominated by an organization whose commitment to innovation and support for technology-driven businesses is well recognized," said Neal Applefeld, president of SeeMore Interactive. "This nomination is a testament to the fact that we are well positioned to provide retailers with a more powerful way of reaching consumers while delivering to shoppers an engaging, interactive experience."<br><br>SeeMore was founded earlier this year by Applefeld, who had a vision of enhancing product engagement to the benefit of both consumers and retailers. SeeMore''s application relies on a combination of augmented reality, image recognition, recommendation engine and location-based technologies to bring catalogs, displays and other retail venues to life in a manner that creates a shareable shopping experience for consumers. For example, a consumer using a smartphone or mobile device can download and watch in-depth product videos that can be shared with their social networks and be purchased through an e-commerce function. SeeMore''s technology will allow consumers to interact with products more than ever before.<br><br>This will be the 17th year that Tech Columbus will be presenting its Innovation Awards to individuals, teams and companies that have outstanding achievements and have transformed their business to promote the economy. Finalists will be named at the Innovation Awards event on February 7, 2013, at the Greater Columbus Convention Center. Each nominee has made an impact in Central Ohio and is expected to grow and make more contributions to the community.\r\n', '2012-09-27 00:00:00', '', ''),
(5, 'Proud Sponsor of Team Bexley in Pelotonia ''12', 'SeeMore Interactive was honored to support Team Bexley in Pelotonia 2012.', '', '1', '1', '', 'SeeMore Interactive was honored to support Team Bexley in Pelotonia 12, an annual event that includes cycling and volunteerism in a grassroots effort to raise money for cancer research. Because of the generosity of Pelotonia''s funding partners, 100 percent of all donations goes to life-saving cancer research. Click <a href="https://www.mypelotonia.org/team_profile.jsp?MemberID=2583">here</a> to learn about Team Bexley and Pelotonia.\r\n', '2012-07-01 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `press`
--

CREATE TABLE IF NOT EXISTS `press` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL,
  `url` varchar(225) NOT NULL,
  `publication` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `press`
--

INSERT INTO `press` (`id`, `title`, `url`, `publication`, `created_date`) VALUES
(1, 'SeeMore Interactive''s Mobile Technology Makes its Way into Major Retail Stores', 'http://www.prweb.com/releases/2013/1/prweb10284909.htm', '1', '2013-01-03 00:00:00'),
(2, 'SeeMore Delivers Personalized Brand Engagement', 'http://www.retailtouchpoints.com/solution-spotlight/2138-seemore-turns-delivers-personalized-brand-engagement', '1', '2012-12-18 00:00:00'),
(3, 'SeeMore Interactive Names Board of Directors', 'http://www.prweb.com/releases/2012/9/prweb9869055.htm', '1', '2012-09-05 00:00:00'),
(4, 'SeeMore Interactive Raises $750,000 for Patent-Pending Mobile Solution', 'http://news.yahoo.com/seemore-interactive-raises-750-000-patent-pending-mobile-193024482.html', '1', '2012-08-09 00:00:00'),
(5, 'SeeMore Interactive lands $350K TechColumbus investment', 'http://www.bizjournals.com/columbus/morning_call/2012/05/seemore-interactive-lands-35k.html?surround=etf&ana=e_article', '1', '2012-05-31 00:00:00'),
(6, 'Augmented-reality shopping app SeeMore lands TechColumbus investment', 'http://www.bizjournals.com/columbus/news/2012/05/30/augmented-reality-shopping-app-seemore.html?surround=etf&ana=e_article', '1', '2012-05-30 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  UNIQUE KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`group_id`, `group_name`) VALUES
(1, 'Super Administrator'),
(2, 'Administrator');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
