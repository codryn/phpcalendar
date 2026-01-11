# Calendar Profiles Reference

Complete reference for all built-in calendar profiles in PHPCalendar.

## Table of Contents

- [Gregorian Calendar](#gregorian-calendar)
- [Fantasy Calendars](#fantasy-calendars)
  - [Faerûn (Forgotten Realms)](#faerûn-forgotten-realms)
  - [Golarion (Pathfinder)](#golarion-pathfinder)
  - [Das Schwarze Auge](#das-schwarze-auge)
  - [Eberron](#eberron)
  - [Dragonlance](#dragonlance)
  - [Greyhawk](#greyhawk)

---

## Gregorian Calendar

**Profile Name:** `gregorian`

The standard international calendar system.

### Specifications

- **Months:** 12
- **Days per Year:** 365 (366 in leap years)
- **Leap Year Rule:** Year divisible by 4, except century years unless divisible by 400
- **Epoch Notation:** BCE (Before Common Era) / CE (Common Era)

### Month Structure

| Month | Name      | Days (Normal) | Days (Leap) |
|-------|-----------|---------------|-------------|
| 1     | January   | 31            | 31          |
| 2     | February  | 28            | 29          |
| 3     | March     | 31            | 31          |
| 4     | April     | 30            | 30          |
| 5     | May       | 31            | 31          |
| 6     | June      | 30            | 30          |
| 7     | July      | 31            | 31          |
| 8     | August    | 31            | 31          |
| 9     | September | 30            | 30          |
| 10    | October   | 31            | 31          |
| 11    | November  | 30            | 30          |
| 12    | December  | 31            | 31          |

### Usage Example

```php
$calendar = Calendar::fromProfile('gregorian');

$date = $calendar->parse('December 25, 2024');
echo $calendar->format($date, 'l, F j, Y'); // Wednesday, December 25, 2024

echo $calendar->isLeapYear(2024) ? 'Leap' : 'Normal'; // Leap
echo $calendar->getDaysInMonth(2, 2024); // 29
```

---

## Fantasy Calendars

**Important Notice:** All fantasy RPG calendar profiles include copyright notices accessible via the `getCopyrightNotice()` method on the profile. These notices acknowledge that the calendar names, month names, and associated terminology are the property of their respective owners and are used solely for non-commercial purposes to help game masters and players keep track of their campaigns.

### Faerûn (Forgotten Realms)

**Profile Name:** `faerun`

The Harptos calendar system used in the Forgotten Realms D&D setting.

#### Specifications

- **Months:** 12 regular months
- **Festival Days:** 5 annual festivals (Midwinter, Greengrass, Midsummer, Highharvestide, Feast of the Moon) + Shieldmeet (leap years)
- **Days per Year:** 365 (366 with Shieldmeet)
- **Leap Year Rule:** Every 4 years (Shieldmeet is added)
- **Epoch Notation:** DR (Dale Reckoning)
- **Copyright:** Calendar names and terminology are property of Wizards of the Coast LLC

#### Month Structure

| # | Name       | Days | Notes |
|---|------------|------|-------|
| 1 | Hammer     | 30   | |
| - | *Midwinter* | 1 | Festival after Hammer |
| 2 | Alturiak   | 30   | |
| 3 | Ches       | 30   | |
| 4 | Tarsakh    | 30   | |
| - | *Greengrass* | 1 | Festival after Tarsakh |
| 5 | Mirtul     | 30   | |
| 6 | Kythorn    | 30   | |
| 7 | Flamerule  | 30   | |
| - | *Midsummer* | 1 | Festival after Flamerule |
| - | *(Shieldmeet)* | 1 | Leap day (every 4 years) |
| 8 | Eleasis    | 30   | |
| 9 | Eleint     | 30   | |
| - | *Highharvestide* | 1 | Festival after Eleint |
| 10 | Marpenoth  | 30   | |
| 11 | Uktar      | 30   | |
| - | *Feast of the Moon* | 1 | Festival after Uktar |
| 12 | Nightal    | 30   | |

**Total:** 12 months × 30 days + 5 festivals = 365 days (366 with Shieldmeet)

**Note:** Festival days and Shieldmeet are nameless days that exist between months and are automatically accounted for in date arithmetic.

#### Usage Example

```php
$calendar = Calendar::fromProfile('faerun');

$date = $calendar->parse('1 Hammer 1492 DR');
echo $calendar->format($date, 'd F Y'); // 1 Hammer 1492

// Check if it's a leap year (Shieldmeet)
if ($calendar->isLeapYear(1492)) {
    echo "Shieldmeet occurs this year!";
}

// Calculate days between dates (includes festival days)
$start = $calendar->parse('1 Hammer 1492 DR');
$end = $calendar->parse('1 Hammer 1493 DR');
$span = $calendar->diff($start, $end);
echo $span->getTotalDays(); // 366 (includes 5 festivals + Shieldmeet)

// Get nameless days configuration
$namelessDays = $calendar->getProfile()->getNamelessDays();
// Returns configuration for Midwinter, Greengrass, Midsummer (+Shieldmeet), Highharvestide, Feast of the Moon
```

---

### Golarion (Pathfinder)

**Profile Name:** `golarion`

The Absalom Reckoning calendar from Pathfinder RPG's Golarion setting.

#### Specifications

- **Months:** 12
- **Days per Year:** 365 (366 in leap years)
- **Leap Year Rule:** Every 8 years
- **Epoch Notation:** AR (Absalom Reckoning)
- **Copyright:** Calendar names and terminology are property of Paizo Inc.

#### Month Structure

| Month | Name       | Days (Normal) | Days (Leap) |
|-------|------------|---------------|-------------|
| 1     | Abadius    | 31            | 31          |
| 2     | Calistril  | 28            | 29          |
| 3     | Pharast    | 31            | 31          |
| 4     | Gozran     | 30            | 30          |
| 5     | Desnus     | 31            | 31          |
| 6     | Sarenith   | 30            | 30          |
| 7     | Erastus    | 31            | 31          |
| 8     | Arodus     | 31            | 31          |
| 9     | Rova       | 30            | 30          |
| 10    | Lamashan   | 31            | 31          |
| 11    | Neth       | 30            | 30          |
| 12    | Kuthona    | 31            | 31          |

#### Usage Example

```php
$calendar = Calendar::fromProfile('golarion');

$date = $calendar->parse('15 Rova 4724 AR');
echo $calendar->format($date, 'd F Y'); // 15 Rova 4724

// Leap year every 8 years
echo $calendar->isLeapYear(4720) ? 'Leap' : 'Normal'; // Leap
echo $calendar->isLeapYear(4721) ? 'Leap' : 'Normal'; // Normal
```

---

### Das Schwarze Auge

**Profile Name:** `dsa`

The Aventurian calendar from Das Schwarze Auge (The Dark Eye) RPG.

#### Specifications

- **Months:** 12 regular months
- **Nameless Days:** 5 nameless days at year end
- **Days per Year:** 365 (always constant)
- **Leap Year Rule:** None (no leap years)
- **Epoch Notation:** BF (Bosparans Fall)
- **Copyright:** Calendar names and terminology are property of Ulisses Spiele GmbH

#### Month Structure

| # | Name        | Days |
|---|-------------|------|
| 1 | Praios      | 30   |
| 2 | Rondra      | 30   |
| 3 | Efferd      | 30   |
| 4 | Travia      | 30   |
| 5 | Boron       | 30   |
| 6 | Hesinde     | 30   |
| 7 | Firun       | 30   |
| 8 | Tsa         | 30   |
| 9 | Phex        | 30   |
| 10 | Peraine     | 30   |
| 11 | Ingerimm    | 30   |
| 12 | Rahja       | 30   |
| - | *5 Nameless Days* | 5 | After Rahja, at year end |

**Total:** 12 months × 30 days + 5 nameless days = 365 days

**Note:** The nameless days are automatically accounted for in date arithmetic and calculations.

#### Usage Example

```php
$calendar = Calendar::fromProfile('dsa');

$date = $calendar->parse('12 Praios 1045 BF');
echo $calendar->format($date, 'd F Y'); // 12 Praios 1045

// No leap years in Aventuria
echo $calendar->isLeapYear(1045) ? 'Leap' : 'Normal'; // Always Normal

// Calculate days in a year (includes nameless days)
$start = $calendar->parse('1 Praios 1045 BF');
$end = $calendar->parse('1 Praios 1046 BF');
$span = $calendar->diff($start, $end);
echo $span->getTotalDays(); // 365 (includes 5 nameless days)

// Get nameless days configuration
$namelessDays = $calendar->getProfile()->getNamelessDays();
// Returns configuration for the 5 nameless days at year end
```

---

### Eberron

**Profile Name:** `eberron`

The Galifar Calendar from D&D's Eberron setting.

#### Specifications

- **Months:** 12
- **Days per Year:** 336 (always constant)
- **Leap Year Rule:** None (no leap years)
- **Epoch Notation:** YK (Years of Kingdom)
- **Copyright:** Calendar names and terminology are property of Wizards of the Coast LLC

#### Month Structure

All months have exactly 28 days (4 weeks each).

| Month | Name      | Days |
|-------|-----------|------|
| 1     | Zarantyr  | 28   |
| 2     | Olarune   | 28   |
| 3     | Therendor | 28   |
| 4     | Eyre      | 28   |
| 5     | Dravago   | 28   |
| 6     | Nymm      | 28   |
| 7     | Lharvion  | 28   |
| 8     | Barrakas  | 28   |
| 9     | Rhaan     | 28   |
| 10    | Sypheros  | 28   |
| 11    | Aryth     | 28   |
| 12    | Vult      | 28   |

#### Usage Example

```php
$calendar = Calendar::fromProfile('eberron');

$date = $calendar->parse('10 Olarune 998 YK');
echo $calendar->format($date, 'd F Y'); // 10 Olarune 998

// Perfect 4-week months
echo $calendar->getDaysInMonth(1, 998); // 28 (always)
echo $calendar->getDaysInMonth(6, 998); // 28 (always)

// No leap years
echo $calendar->isLeapYear(998) ? 'Leap' : 'Normal'; // Always Normal
```

---

### Dragonlance

**Profile Name:** `dragonlance`

The Krynn calendar from D&D's Dragonlance setting.

#### Specifications

- **Months:** 12
- **Days per Year:** 365 (366 in leap years)
- **Leap Year Rule:** Gregorian rules (divisible by 4, except centuries unless divisible by 400)
- **Epoch Notation:** PC (Pre-Cataclysm) / AC (After Cataclysm)
- **Copyright:** Calendar names and terminology are property of Wizards of the Coast LLC

#### Month Structure

| Month | Name         | Days (Normal) | Days (Leap) |
|-------|--------------|---------------|-------------|
| 1     | Winter Night | 31            | 31          |
| 2     | Winter Deep  | 28            | 29          |
| 3     | Spring Dawning| 31           | 31          |
| 4     | Spring Rain  | 30            | 30          |
| 5     | Summer Home  | 31            | 31          |
| 6     | Summer Run   | 30            | 30          |
| 7     | Midsummer    | 31            | 31          |
| 8     | Autumn Harvest| 31           | 31          |
| 9     | Autumn Twilight| 30          | 30          |
| 10    | Phoenix      | 31            | 31          |
| 11    | Spreading    | 30            | 30          |
| 12    | Deepkolt     | 31            | 31          |

#### Usage Example

```php
$calendar = Calendar::fromProfile('dragonlance');

$preCataclysm = $calendar->parse('100 Phoenix 0 PC');
$postCataclysm = $calendar->parse('1 Phoenix 1 AC');

echo $calendar->format($preCataclysm, 'd F Y'); // 100 Phoenix 0
echo $calendar->format($postCataclysm, 'd F Y'); // 1 Phoenix 1

// Leap year using Gregorian rules
echo $calendar->isLeapYear(352) ? 'Leap' : 'Normal'; // Leap (352 AC)
```

---

### Greyhawk

**Profile Name:** `greyhawk`

The Common Year calendar from D&D's World of Greyhawk.

#### Specifications

- **Months:** 12 regular months + 4 festival weeks
- **Days per Year:** 364 (always constant)
- **Leap Year Rule:** None (no leap years)
- **Epoch Notation:** CY (Common Year)
- **Copyright:** Calendar names and terminology are property of Wizards of the Coast LLC

#### Month Structure

| Month | Name           | Days | Type     |
|-------|----------------|------|----------|
| 1     | Needfest       | 7    | Festival |
| 2     | Fireseek       | 28   | Month    |
| 3     | Readying       | 28   | Month    |
| 4     | Coldeven       | 28   | Month    |
| 5     | Growfest       | 7    | Festival |
| 6     | Planting       | 28   | Month    |
| 7     | Flocktime      | 28   | Month    |
| 8     | Wealsun        | 28   | Month    |
| 9     | Richfest       | 7    | Festival |
| 10    | Reaping        | 28   | Month    |
| 11    | Goodmonth      | 28   | Month    |
| 12    | Harvester      | 28   | Month    |
| 13    | Brewfest       | 7    | Festival |
| 14    | Patchwall      | 28   | Month    |
| 15    | Ready'reat     | 28   | Month    |
| 16    | Sunsebb        | 28   | Month    |

**Total:** 12 months × 28 days + 4 festivals × 7 days = 364 days

#### Usage Example

```php
$calendar = Calendar::fromProfile('greyhawk');

$date = $calendar->parse('1 Needfest 591 CY');
echo $calendar->format($date, 'd F Y'); // 1 Needfest 591

// Festival weeks
$needfest = $calendar->parse('5 Needfest 591 CY'); // Day 5 of Needfest
$richfest = $calendar->parse('3 Richfest 591 CY'); // Day 3 of Richfest

// Regular months are always 28 days
echo $calendar->getDaysInMonth(2, 591); // 28 (Fireseek)

// No leap years in Greyhawk
echo $calendar->isLeapYear(591) ? 'Leap' : 'Normal'; // Always Normal
```

---

## Comparison Table

| Calendar     | Months | Days/Year | Leap Years | Epoch    | Setting           |
|--------------|--------|-----------|------------|----------|-------------------|
| Gregorian    | 12     | 365-366   | Every 4*   | BCE/CE   | Real World        |
| Faerûn       | 12+5   | 365-366   | Every 4    | DR       | Forgotten Realms  |
| Golarion     | 12     | 365-366   | Every 8    | AR       | Pathfinder        |
| DSA          | 12+5   | 365       | None       | BF       | Das Schwarze Auge |
| Eberron      | 12     | 336       | None       | YK       | Eberron           |
| Dragonlance  | 12     | 365-366   | Every 4*   | PC/AC    | Dragonlance       |
| Greyhawk     | 12+4   | 364       | None       | CY       | Greyhawk          |

*Leap year rule: Divisible by 4, except century years unless divisible by 400

---

## See Also

- [API Documentation](API.md) - Complete API reference
- [Usage Guide](USAGE.md) - Common usage patterns
- [Custom Calendars](CUSTOM_CALENDARS.md) - Create your own calendar profiles
