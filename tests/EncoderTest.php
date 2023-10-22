<?php declare(strict_types=1);

use Covaleski\DataEncoding\Base16;
use Covaleski\DataEncoding\Base32;
use Covaleski\DataEncoding\Base32Hex;
use Covaleski\DataEncoding\Base64;
use Covaleski\DataEncoding\Base64Url;
use PHPUnit\Framework\TestCase;

/**
 * Tests the encoder facade class.
 */
final class EncoderTest extends TestCase
{
    /**
     * Test general encoding and decoding.
     */
    public function testCanEncodeAndDecode(): void
    {
        // Define multiple test strings.
        $strings = [
            ['', [
                Base16::class => '',
                Base32::class => '',
                Base32Hex::class => '',
                Base64::class => '',
                Base64Url::class => '',
            ]],
            ['{}|"@', [
                Base16::class => strtoupper(bin2hex('{}|"@')),
                Base32::class => 'PN6XYISA',
                Base32Hex::class => 'FDUNO8I0',
                Base64::class => base64_encode('{}|"@'),
                Base64Url::class => str_replace(['+', '/'], ['-', '_'], base64_encode('{}|"@')),
            ]],
            ['Dunder Mifflin Paper Company, Inc.', [
                Base16::class => strtoupper(bin2hex('Dunder Mifflin Paper Company, Inc.')),
                Base32::class => 'IR2W4ZDFOIQE22LGMZWGS3RAKBQXAZLSEBBW63LQMFXHSLBAJFXGGLQ=',
                Base32Hex::class => '8HQMSP35E8G4QQB6CPM6IRH0A1GN0PBI411MURBGC5N7IB1095N66BG=',
                Base64::class => base64_encode('Dunder Mifflin Paper Company, Inc.'),
                Base64Url::class => str_replace(['+', '/'], ['-', '_'], base64_encode('Dunder Mifflin Paper Company, Inc.')),
            ]],
            ['Museu de Arte de São Paulo', [
                Base16::class => strtoupper(bin2hex('Museu de Arte de São Paulo')),
                Base32::class => 'JV2XGZLVEBSGKICBOJ2GKIDEMUQFHQ5DN4QFAYLVNRXQ====',
                Base32Hex::class => '9LQN6PBL41I6A821E9Q6A834CKG57GT3DSG50OBLDHNG====',
                Base64::class => base64_encode('Museu de Arte de São Paulo'),
                Base64Url::class => str_replace(['+', '/'], ['-', '_'], base64_encode('Museu de Arte de São Paulo')),
            ]],
            ['南京路', [
                Base16::class => strtoupper(bin2hex('南京路')),
                Base32::class => '4WGZPZF2VTULPLY=',
                Base32Hex::class => 'SM6PFP5QLJKBFBO=',
                Base64::class => base64_encode('南京路'),
                Base64Url::class => str_replace(['+', '/'], ['-', '_'], base64_encode('南京路')),
            ]],
            ['جيزة يسروبوليس', [
                Base16::class => strtoupper(bin2hex('جيزة يسروبوليس')),
                Base32::class => '3CWNTCWYWLMKSIGZRLMLHWFR3GENRKGZRDMYJWMK3CZQ====',
                Base32Hex::class => 'R2MDJ2MOMBCAI86PHBCB7M5HR64DHA6PH3CO9MCAR2PG====',
                Base64::class => base64_encode('جيزة يسروبوليس'),
                Base64Url::class => str_replace(['+', '/'], ['-', '_'], base64_encode('جيزة يسروبوليس')),
            ]],
            ['ताज महल', [
                Base16::class => strtoupper(bin2hex('ताज महल')),
                Base32::class => '4CSKJYFEX3QKJHBA4CSK5YFEXHQKJMQ=',
                Base32Hex::class => 'S2IA9O54NRGA9710S2IATO54N7GA9CG=',
                Base64::class => base64_encode('ताज महल'),
                Base64Url::class => str_replace(['+', '/'], ['-', '_'], base64_encode('ताज महल')),
            ]],
            ['Όλυμπος', [
                Base16::class => strtoupper(bin2hex('Όλυμπος')),
                Base32::class => 'Z2GM5O6PQXHLZT4AZ2747AQ=',
                Base32Hex::class => 'PQ6CTEUFGN7BPJS0PQVSV0G=',
                Base64::class => base64_encode('Όλυμπος'),
                Base64Url::class => str_replace(['+', '/'], ['-', '_'], base64_encode('Όλυμπος')),
            ]],
            ["\x00", [
                Base16::class => strtoupper(bin2hex("\x00")),
                Base32::class => 'AA======',
                Base32Hex::class => '00======',
                Base64::class => base64_encode("\x00"),
                Base64Url::class => str_replace(['+', '/'], ['-', '_'], base64_encode("\x00")),
            ]],
            ["\x00\x00\x00\x00\x00", [
                Base16::class => strtoupper(bin2hex("\x00\x00\x00\x00\x00")),
                Base32::class => 'AAAAAAAA',
                Base32Hex::class => '00000000',
                Base64::class => base64_encode("\x00\x00\x00\x00\x00"),
                Base64Url::class => str_replace(['+', '/'], ['-', '_'], base64_encode("\x00\x00\x00\x00\x00")),
            ]],
        ];

        // Test each string.
        foreach ($strings as $config) {
            $expected_decoding = (string) $config[0];
            $encodings = (array) $config[1];
            // Test the string with all RFC 4648 encoding styles.
            foreach ($encodings as $class_name => $expected_encoding) {
                // Test encoding.
                $this->assertSame(
                    $expected_encoding,
                    $class_name::encode($expected_decoding),
                    sprintf('Using "%s" to encode %s', $class_name, $expected_decoding),
                );
                // Test decoding.
                $this->assertSame(
                    $expected_decoding,
                    $class_name::decode($expected_encoding),
                    sprintf('Using "%s" to decode %s', $class_name, $expected_encoding),
                );
            }
        }
    }

    /**
     * Test the creation of a custom encoding class with ASCII characters.
     */
    public function testCanCustomize(): void
    {
        require_once __DIR__ . '/includes/Base2Test.php';
        require_once __DIR__ . '/includes/Base4Test.php';

        // Test base2.
        $expected_decoding = 'foo';
        $expected_encoding = '011001100110111101101111';
        $this->assertSame(
            $expected_encoding,
            Base2Test::encode($expected_decoding),
            sprintf('Using "%s" to decode %s', Base2Test::class, $expected_encoding),
        );
        $this->assertSame(
            $expected_decoding,
            Base2Test::decode($expected_encoding),
            sprintf('Using "%s" to decode %s', Base2Test::class, $expected_encoding),
        );

        // Test base 4
        $expected_decoding = 'foo';
        $expected_encoding = 'BCBCBCDDBCDD';
        $this->assertSame(
            $expected_encoding,
            Base4Test::encode($expected_decoding),
            sprintf('Using "%s" to decode %s', Base4Test::class, $expected_encoding),
        );
        $this->assertSame(
            $expected_decoding,
            Base4Test::decode($expected_encoding),
            sprintf('Using "%s" to decode %s', Base4Test::class, $expected_encoding),
        );
    }

    /**
     * Test the creation of a custom encoding class with non-ASCII characters.
     */
    public function testCanCustomizeUnicode(): void
    {
        require_once __DIR__ . '/includes/BaseEmojiTest.php';
        $expected_decoding = 'foo';
        $expected_encoding = '💀😔🤡✊💅👀👀💅';
        $this->assertSame(
            $expected_encoding,
            BaseEmojiTest::encode($expected_decoding),
            sprintf('Using "%s" to decode %s', BaseEmojiTest::class, $expected_encoding),
        );
        $this->assertSame(
            $expected_decoding,
            BaseEmojiTest::decode($expected_encoding),
            sprintf('Using "%s" to decode %s', BaseEmojiTest::class, $expected_encoding),
        );
    }
}













