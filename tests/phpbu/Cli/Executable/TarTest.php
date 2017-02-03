<?php
namespace phpbu\App\Cli\Executable;

/**
 * Tar Test
 *
 * @package    phpbu
 * @subpackage tests
 * @author     Sebastian Feldmann <sebastian@phpbu.de>
 * @copyright  Sebastian Feldmann <sebastian@phpbu.de>
 * @license    https://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       http://www.phpbu.de/
 * @since      Class available since Release 2.1.0
 */
class TarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests Tar::getCommandLine
     */
    public function testDefault()
    {
        $dir  = sys_get_temp_dir();
        $tarC = dirname($dir);
        $tarD = basename($dir);
        $tar  = new Tar(PHPBU_TEST_BIN);
        $tar->archiveDirectory($dir)->archiveTo('/tmp/foo.tar');

        $this->assertEquals(PHPBU_TEST_BIN . '/tar -cf \'/tmp/foo.tar\' -C \'' . $tarC .  '\' \'' . $tarD . '\'', $tar->getCommand());
    }

    /**
     * Tests Tar::getCommandLine
     */
    public function testignoreFailedRead()
    {
        $dir  = sys_get_temp_dir();
        $tarC = dirname($dir);
        $tarD = basename($dir);
        $tar  = new Tar(PHPBU_TEST_BIN);
        $tar->archiveDirectory($dir)->archiveTo('/tmp/foo.tar')->ignoreFailedRead(true);

        $this->assertEquals(PHPBU_TEST_BIN . '/tar --ignore-failed-read -cf \'/tmp/foo.tar\' -C \'' . $tarC .  '\' \'' . $tarD . '\'', $tar->getCommand());
    }

    /**
     * Tests Tar::getCommandLinePrintable
     */
    public function testDefaultPrintable()
    {
        $dir  = sys_get_temp_dir();
        $tarC = dirname($dir);
        $tarD = basename($dir);
        $tar  = new Tar(PHPBU_TEST_BIN);
        $tar->archiveDirectory($dir)->archiveTo('/tmp/foo.tar');

        $this->assertEquals(PHPBU_TEST_BIN . '/tar -cf \'/tmp/foo.tar\' -C \'' . $tarC .  '\' \'' . $tarD . '\'', $tar->getCommandPrintable());
    }

    /**
     * Tests Tar::getCommandLine
     */
    public function testCompressionGzip()
    {
        $dir  = sys_get_temp_dir();
        $tarC = dirname($dir);
        $tarD = basename($dir);
        $tar  = new Tar(PHPBU_TEST_BIN);
        $tar->archiveDirectory($dir)->archiveTo('/tmp/foo.tar.gz')->useCompression('gzip');

        $this->assertEquals(
            PHPBU_TEST_BIN . '/tar -zcf \'/tmp/foo.tar.gz\' -C \'' . $tarC .  '\' \'' . $tarD . '\'',
            $tar->getCommandLine()
        );
    }

    /**
     * Tests Tar::getCommandLine
     */
    public function testCompressionBzip2()
    {
        $dir  = sys_get_temp_dir();
        $tarC = dirname($dir);
        $tarD = basename($dir);
        $tar  = new Tar(PHPBU_TEST_BIN);
        $tar->archiveDirectory($dir)->archiveTo('/tmp/foo.tar.bzip2')->useCompression('bzip2');

        $this->assertEquals(
            PHPBU_TEST_BIN . '/tar -jcf \'/tmp/foo.tar.bzip2\' -C \'' . $tarC .  '\' \'' . $tarD . '\'',
            $tar->getCommandLine()
        );
    }

    /**
     * Tests Tar::getCommandLine
     */
    public function testRemoveSourceDir()
    {
        $dir  = sys_get_temp_dir();
        $tarC = dirname($dir);
        $tarD = basename($dir);
        $tar  = new Tar(PHPBU_TEST_BIN);
        $tar->archiveDirectory($dir)->archiveTo('/tmp/foo.tar')->removeSourceDirectory(true);

        $this->assertEquals(
            '(' . PHPBU_TEST_BIN . '/tar -cf \'/tmp/foo.tar\' -C \'' . $tarC .  '\' \'' . $tarD . '\''
              . ' && rm -rf \'' . $dir . '\')',
            $tar->getCommandLine()
        );
    }

    /**
     * Tests Tar::getCommandLine
     *
     * @expectedException \phpbu\App\Exception
     */
    public function testWithoutSource()
    {
        $tar  = new Tar(PHPBU_TEST_BIN);
        $tar->getCommand();
    }

    /**
     * Tests Tar::getCommandLine
     *
     * @expectedException \phpbu\App\Exception
     */
    public function testWithoutTarget()
    {
        $tar  = new Tar(PHPBU_TEST_BIN);
        $tar->archiveDirectory(__DIR__);
        $tar->getCommand();
    }

    /**
     * Tests Tar::archiveDirectory
     *
     * @expectedException \phpbu\App\Exception
     */
    public function testSourceNotCWD()
    {
        $tar  = new Tar(PHPBU_TEST_BIN);
        $tar->archiveDirectory('.');
    }

    /**
     * Tests Tar::isCompressionValid
     */
    public function testIsCompressionValid()
    {
        $this->assertTrue(Tar::isCompressionValid('gzip'));
        $this->assertTrue(Tar::isCompressionValid('bzip2'));
        $this->assertFalse(Tar::isCompressionValid('zip'));
    }

    /**
     * Tests Tar::handlesCompression
     */
    public function testHandlesCompression()
    {
        $tar = new Tar(PHPBU_TEST_BIN);

        $this->assertFalse($tar->handlesCompression());

        $tar->useCompression('zip');

        $this->assertFalse($tar->handlesCompression());

        $tar->useCompression('gzip');

        $this->assertTrue($tar->handlesCompression());
    }
}
