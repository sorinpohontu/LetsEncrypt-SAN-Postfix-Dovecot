<?php

/**
 * Lets Encrypt SAN certificates for Postfix / Dovecot on Froxlor Control Panel
 *
 * @author      Sorin Pohontu <sorin@frontline.ro>
 * @copyright   2020 Frontline softworks <http://www.frontline.ro>
 *
 * @version     1.00
 * @since       2020.05.17
 *
 */

/* Config */
require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php');

/* Check getSSL */
if (checkGetSSLInstall()) {
    try {
        /* Database connection */
        $db = new Db($sql['host'], $sql['db'], $sql['user'], $sql['password']);

        $sans = getEmailHosts($db);
        if ($sans) {
            /* Rewrite getSSL config with current SANs */
            writeGetSSLConfig($sans);

            /* Start getSSL */
            exec(GETSSL_BIN . GETSSL_BIN_OPTIONS . ' -w ' . GETSSL_CONFIG_PATH . ' -d ' . GETSSL_MAIN_DOMAIN, $pOutput, $pExitCode);
            if (DEBUG) {
                print("Exit code: $pExitCode\n");
                print("Output: " . print_r($pOutput, true) . "\n");
            }
        }
    } catch (PDOException $e) {
        print("Error connecting to Control Panel database!\n");
    }
}

?>
