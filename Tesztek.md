![Teszek](/documents/Kepek/tesztek.png)

## Példa

```php
    public function test_teams_table_structure()
    {
        $columns = ['id' => 'int', 'competitionId' => 'int', 'name' => 'string', 'school' => 'string', 'userId' => 'int'];
        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('teams', $column), "Teams table missing $column column");
            $this->assertColumnType('teams', $column, $type);
        }
        $this->assertForeignKeyExists('teams', 'competitionId', 'competitions', 'id');
        $this->assertForeignKeyExists('teams', 'userId', 'users', 'id');
    }
```