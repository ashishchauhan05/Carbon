<?php
declare(strict_types=1);

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\CarbonImmutable;

use Carbon\CarbonImmutable as Carbon;
use DateTime;
use DateTimeZone;
use Tests\AbstractTestCase;

class ConstructTest extends AbstractTestCase
{
    public function testCreatesAnInstanceDefaultToNow()
    {
        $c = new Carbon();
        $now = Carbon::now();
        $this->assertInstanceOfCarbon($c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertCarbon($c, $now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second);
    }

    public function testCreatesAnInstanceFromADateTime()
    {
        $c = new Carbon(Carbon::parse('2009-09-09 09:09:09'));

        $this->assertSame('2009-09-09 09:09:09 America/Toronto', $c->format('Y-m-d H:i:s e'));

        $c = new Carbon(new DateTime('2009-09-09 09:09:09'));

        $this->assertSame('2009-09-09 09:09:09 America/Toronto', $c->format('Y-m-d H:i:s e'));

        $c = new Carbon(new DateTime('2009-09-09 09:09:09', new DateTimeZone('Europe/Paris')));

        $this->assertSame('2009-09-09 09:09:09 Europe/Paris', $c->format('Y-m-d H:i:s e'));

        $c = new Carbon(new DateTime('2009-09-09 09:09:09'), 'Europe/Paris');

        $this->assertSame('2009-09-09 15:09:09 Europe/Paris', $c->format('Y-m-d H:i:s e'));

        $c = new Carbon(new DateTime('2009-09-09 09:09:09', new DateTimeZone('Asia/Tokyo')), 'Europe/Paris');

        $this->assertSame('2009-09-09 02:09:09 Europe/Paris', $c->format('Y-m-d H:i:s e'));
    }

    public function testParseCreatesAnInstanceDefaultToNow()
    {
        $c = Carbon::parse();
        $now = Carbon::now();
        $this->assertInstanceOfCarbon($c);
        $this->assertSame($now->tzName, $c->tzName);
        $this->assertCarbon($c, $now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second);
    }

    public function testWithFancyString()
    {
        Carbon::setTestNow(Carbon::today());
        $c = new Carbon('first day of January 2008');
        $this->assertCarbon($c, 2008, 1, 1, 0, 0, 0);
    }

    public function testParseWithFancyString()
    {
        Carbon::setTestNow(Carbon::today());
        $c = Carbon::parse('first day of January 2008');
        $this->assertCarbon($c, 2008, 1, 1, 0, 0, 0);
    }

    public function testDefaultTimezone()
    {
        $c = new Carbon('now');
        $this->assertSame('America/Toronto', $c->tzName);
    }

    public function testParseWithDefaultTimezone()
    {
        $c = Carbon::parse('now');
        $this->assertSame('America/Toronto', $c->tzName);
    }

    public function testSettingTimezone()
    {
        $timezone = 'Europe/London';
        $dtz = new DateTimeZone($timezone);
        $dt = new DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int) $dt->format('I');

        $c = new Carbon('now', $dtz);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame($dayLightSavingTimeOffset, $c->offsetHours);
    }

    public function testParseSettingTimezone()
    {
        $timezone = 'Europe/London';
        $dtz = new DateTimeZone($timezone);
        $dt = new DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int) $dt->format('I');

        $c = Carbon::parse('now', $dtz);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame($dayLightSavingTimeOffset, $c->offsetHours);
    }

    public function testSettingTimezoneWithString()
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new DateTimeZone($timezone);
        $dt = new DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int) $dt->format('I');

        $c = new Carbon('now', $timezone);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->offsetHours);
    }

    public function testParseSettingTimezoneWithString()
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new DateTimeZone($timezone);
        $dt = new DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int) $dt->format('I');

        $c = Carbon::parse('now', $timezone);
        $this->assertSame($timezone, $c->tzName);
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->offsetHours);
    }

    public function testMockingWithMicroseconds()
    {
        $c = new Carbon(Carbon::now()->toDateTimeString().'.123456');
        Carbon::setTestNow($c);

        $mockedC = Carbon::now();
        $this->assertTrue($c->eq($mockedC));

        Carbon::setTestNow();
    }

    public function testTimestamp()
    {
        $date = new Carbon(1367186296);
        $this->assertSame('Sunday 28 April 2013 21:58:16', $date->format('l j F Y H:i:s'));
    }
}
