<?php

namespace Tests\Unit;

use App\Models\team_at_station;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Competition;
use App\Models\Station;
use App\Models\ResultType;
use App\Models\TeamAtStation;
use App\Models\MemberResultAtStation;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use DatabaseTransactions;

    protected function assertColumnType(string $table, string $column, string $type)
    {
        $actualType = Schema::getColumnType($table, $column);
        $expectedType = ($type === 'string') ? 'varchar' : $type;
        if ($type === 'boolean' && $actualType === 'tinyint') {
            $actualType = 'boolean';
        }
        $this->assertEquals(
            $expectedType,
            $actualType,
            "Column $table.$column should be type $expectedType (got $actualType)"
        );
    }

    protected function assertForeignKeyExists(string $table, string $column, string $foreignTable, string $foreignColumn)
    {
        $foreignKeys = DB::select("
            SELECT REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
        ", [config('database.connections.mysql.database'), $table, $column]);

        $exists = collect($foreignKeys)->contains(fn($fk) => $fk->REFERENCED_TABLE_NAME === $foreignTable && $fk->REFERENCED_COLUMN_NAME === $foreignColumn);

        $this->assertTrue($exists, "Foreign key $table.$column -> $foreignTable.$foreignColumn missing");
    }

    public function test_database_structure()
    {
        $tables = [
            'users',
            'roles',
            'teams',
            'team_members',
            'competitions',
            'stations',
            'result_types',
            'team_at_stations',
            'member_results_at_stations'
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table $table does not exist");
        }
    }

    public function test_users_table_structure()
    {
        $columns = ['id' => 'int', 'name' => 'string', 'email' => 'string', 'password' => 'string', 'roleId' => 'int'];
        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('users', $column), "Users table missing $column column");
            $this->assertColumnType('users', $column, $type);
        }
        $this->assertForeignKeyExists('users', 'roleId', 'roles', 'id');
    }

    public function test_roles_table_structure()
    {
        $columns = ['id' => 'int', 'role' => 'string', 'permission' => 'int'];
        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('roles', $column), "Roles table missing $column column");
            $this->assertColumnType('roles', $column, $type);
        }
    }

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

    public function test_team_members_table_structure()
    {
        $columns = ['id' => 'int', 'teamId' => 'int', 'name' => 'string', 'captain' => 'tinyint'];
        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('team_members', $column), "Team Members table missing $column column");
            $this->assertColumnType('team_members', $column, $type);
        }
        $this->assertForeignKeyExists('team_members', 'teamId', 'teams', 'id');
    }

    public function test_competitions_table_structure()
    {
        $columns = ['id' => 'int', 'name' => 'string', 'date' => 'date', 'location' => 'string', 'registerFrom' => 'date', 'registerTo' => 'date'];
        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('competitions', $column), "Competitions table missing $column column");
            $this->assertColumnType('competitions', $column, $type);
        }
    }

    public function test_stations_table_structure()
    {
        $columns = ['id' => 'int', 'name' => 'string', 'location' => 'string', 'weighting' => 'double', 'moreIsBetter' => 'boolean', 'typeId' => 'int', 'userId' => 'int', 'competitionId' => 'int'];
        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('stations', $column), "Stations table missing $column column");
            $this->assertColumnType('stations', $column, $type);
        }
        $this->assertForeignKeyExists('stations', 'competitionId', 'competitions', 'id');
    }

    public function test_result_types_table_structure()
    {
        $columns = ['id' => 'int', 'type' => 'string'];
        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('result_types', $column), "Result Types table missing $column column");
            $this->assertColumnType('result_types', $column, $type);
        }
    }

    // team_at_stations table structure testing
    public function test_team_at_stations_table_structure()
    {
        $this->assertTrue(Schema::hasTable('team_at_stations'));

        $columns = [
            'id' => 'int',
            'teamId' => 'int',
            'stationId' => 'int',
        ];
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('team_at_stations', $column),
                "team_at_stations table missing $column column"
            );
            $this->assertColumnType('team_at_stations', $column, $type);
        }
        $this->assertForeignKeyExists('team_at_stations', 'teamId', 'teams', 'id');
        $this->assertForeignKeyExists('team_at_stations', 'stationId', 'stations', 'id');
    }

    // member_results_at_stations table structure testing
    public function test_member_results_at_stations_table_structure()
    {
        $this->assertTrue(Schema::hasTable('member_results_at_stations'));

        $columns = [
            'id' => 'int',
            'teamAtStationId' => 'int',
            'teamMemberId' => 'int',
            'result' => 'int',
            'resultTime' => 'time',
        ];
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('member_results_at_stations', $column),
                "member_results_at_stations table missing $column column"
            );
            $this->assertColumnType('member_results_at_stations', $column, $type);
        }
        $this->assertForeignKeyExists('member_results_at_stations', 'teamAtStationId', 'team_at_stations', 'id');
        $this->assertForeignKeyExists('member_results_at_stations', 'teamMemberId', 'team_members', 'id');
    }
}