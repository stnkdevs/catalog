

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `id2248507_catalog`
--

-- --------------------------------------------------------

--
-- Структура таблицы `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `surname` varchar(80) NOT NULL,
  `fname` varchar(80) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `author`
--

INSERT INTO `author` (`id`, `name`, `surname`, `fname`) VALUES
(14, '', 'Стендаль', ''),
(15, 'Антонина', 'Шелехова', 'Михайловна'),
(9, 'Вікторія', 'Палєхова', 'Антонівна'),
(10, 'Валентин', 'Сагайдачний', 'Якович'),
(3, 'Валентина', 'Боренко', 'Кирилівна'),
(7, 'Валентина', 'Марченко', 'Петрівна'),
(6, 'Валерій', 'Майборода', 'Антонович'),
(4, 'Леся', 'Українка', ''),
(11, 'Микола', 'Нестеренко', 'Петрович'),
(8, 'Михайло', 'Булгаков', 'Афанасійович'),
(2, 'Олена', 'Шматько', 'Петрівна'),
(1, 'Тарас', 'Шевченко', 'Григорович'),
(12, 'Франц', 'Кафка', ''),
(13, 'Эрнест', 'Хемінгуей', '');

-- --------------------------------------------------------

--
-- Структура таблицы `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `title` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `book`
--

INSERT INTO `book` (`id`, `title`, `description`, `price`) VALUES
(1, 'Алгебра та початки аналізу', 'Книга розповідає як виконувати математичний аналіз за допомогою інтегрування та диференціювання. Посібник підходить для студентів, школярів 10-11 класів та викладачів.<b>hhhh</b>', 100),
(2, 'Мастер і Маргарита', 'Роман «Майстер і Марґарита» Михайло Булгаков почав, писати 1928 чи 1929 року. Серед дійових осіб у першій редакції не було ані Майстра, ані Марґарити. На початку 1930 року Булгаков свій незакінчений р', 200),
(3, 'Кобзар', 'Неперевершені вірші Т.Г. Шевченко', 75.35),
(4, 'Політекономія', 'Базовий курс політекономії. Містить розділи сучасної мікро- та макроекономіки, інституційної економіки. Базовий курс політекономії. Містить розділи сучасної мікро- та макроекономіки, інституційної еко', 65.35),
(5, 'Історія України', 'Історія України: від Русі до сьогодні охоплює десятки століть. В книзі описано хід найважливіших історичних подій, персоналії та культурні здобутки в різні часи. Буде корисно школярам під час підготов', 120),
(6, 'Вибрані твори зарубіжної літератури', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec qu', 240.38),
(7, 'Секреты здоровья', 'Далеко-далеко за словесными горами в стране гласных и согласных живут рыбные тексты. Вдали от всех живут они в буквенных домах на берегу Семантика большого языкового океана. Маленький ручеек Даль журч', 57.23);

-- --------------------------------------------------------

--
-- Структура таблицы `book_author`
--

CREATE TABLE `book_author` (
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `position` tinyint(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `book_author`
--

INSERT INTO `book_author` (`book_id`, `author_id`, `position`) VALUES
(1, 2, 0),
(1, 6, 1),
(2, 8, 0),
(3, 1, 0),
(4, 9, 0),
(5, 10, 0),
(5, 11, 1),
(6, 12, 0),
(6, 13, 1),
(6, 14, 2),
(7, 15, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `book_janr`
--

CREATE TABLE `book_janr` (
  `book_id` int(11) NOT NULL,
  `janr_id` smallint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `book_janr`
--

INSERT INTO `book_janr` (`book_id`, `janr_id`) VALUES
(1, 6),
(1, 9),
(2, 3),
(3, 1),
(4, 6),
(4, 9),
(5, 9),
(6, 1),
(6, 3),
(6, 4),
(7, 5),
(7, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `janr`
--

CREATE TABLE `janr` (
  `id` smallint(2) NOT NULL,
  `title` varchar(45) NOT NULL,
  `position` tinyint(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `janr`
--

INSERT INTO `janr` (`id`, `title`, `position`) VALUES
(1, 'Поезія', 0),
(2, 'Науково-популярна література', 0),
(3, 'Романи', 0),
(4, 'Оповідання та новели', 0),
(5, 'Кулінарія', 0),
(6, 'Фінанси та економіка', 0),
(7, 'Краса, здоров\'я та спорт', 0),
(8, 'Інформаційні технології', 0),
(9, 'Навчальні посібники', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fullname` (`name`,`surname`,`fname`);

--
-- Индексы таблицы `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `book_author`
--
ALTER TABLE `book_author`
  ADD PRIMARY KEY (`book_id`,`author_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Индексы таблицы `book_janr`
--
ALTER TABLE `book_janr`
  ADD PRIMARY KEY (`book_id`,`janr_id`),
  ADD KEY `janr_id` (`janr_id`);

--
-- Индексы таблицы `janr`
--
ALTER TABLE `janr`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `janr`
--
ALTER TABLE `janr`
  MODIFY `id` smallint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `book_author`
--
ALTER TABLE `book_author`
  ADD CONSTRAINT `book_author_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
  ADD CONSTRAINT `book_author_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`);

--
-- Ограничения внешнего ключа таблицы `book_janr`
--
ALTER TABLE `book_janr`
  ADD CONSTRAINT `book_janr_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
  ADD CONSTRAINT `book_janr_ibfk_2` FOREIGN KEY (`janr_id`) REFERENCES `janr` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
