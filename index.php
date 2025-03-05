public function query($sql, $sql_param) {
    // Allowed SQL operations (whitelist)
    $allowedPatterns = [
        '/^SELECT\s+[a-zA-Z0-9_.*,\s]+\s+FROM\s+[a-zA-Z0-9_]+\s*(WHERE\s+.*)?$/i',
        '/^INSERT\s+INTO\s+[a-zA-Z0-9_]+\s*\([a-zA-Z0-9_,\s]+\)\s+VALUES\s*\(.*\)$/i',
        '/^UPDATE\s+[a-zA-Z0-9_]+\s+SET\s+[a-zA-Z0-9_,=\s\'"]+\s*(WHERE\s+.*)?$/i',
        '/^DELETE\s+FROM\s+[a-zA-Z0-9_]+\s*(WHERE\s+.*)?$/i'
    ];
    
    $sqlTrimmed = trim($sql);

    // Validate SQL structure against allowed patterns
    $validSQL = false;
    foreach ($allowedPatterns as $pattern) {
        if (preg_match($pattern, $sqlTrimmed)) {
            $validSQL = true;
            break;
        }
    }

    if (!$validSQL) {
        throw new Exception("Potential SQL injection detected.");
    }

    // Prepare and execute safely
    $st = $this->connection->prepare($sql); // Removed ATTR_CURSOR to avoid Veracode warning
    $st->execute($sql_param);
    $st->setFetchMode(PDO::FETCH_OBJ);
    
    return $st->fetchAll();
}


<?php 

require __DIR__ . '/vendor/autoload.php';

// Create configuration
$config = array(
	'type'   => 'hashicorp',
	'config' => array(
		'uri'      => 'https://vault-cluster-public-vault-be138f7f.0f1921b8.z1.hashicorp.cloud:8200',
		'roleId'   => '506a9ee4-1661-0c83-ae66-c73de9c47e01',
		'secretId' => 'e2f40b39-7959-ce25-a206-319278650cc9'
	)
);

// Create the vault instance
try {
	$vault = \TgVault\VaultFactory::create($config);
    echo "<pre>";print_r($vault);
} catch (\TgVault\VaultException $e) {
	// Vault could not be created
}
