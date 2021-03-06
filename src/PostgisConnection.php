<?php namespace Shaozeming\LumenPostgis;

class PostgisConnection extends \Bosnadev\Database\PostgresConnection
{
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);

        // Prevent geography type fields from throwing a 'type not found' error.
        $this->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('geography', 'string');
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new Schema\Grammars\PostgisGrammar);
    }


    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new Schema\Builder($this);
    }
}
