SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE `userbot_bind` (
                              `id_user` int(11) NOT NULL,
                              `code` varchar(8) NOT NULL,
                              `id_chat` int(11) NOT NULL,
                              PRIMARY KEY (`code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE `userbot_data` (
                              `id_user` int(11) NOT NULL,
                              `token` text NOT NULL,
                              `secret` varchar(32) NOT NULL,
                              PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
