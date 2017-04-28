<?php

namespace Kingsley\Mentions\Test;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends Orchestra
{
    protected $testCommentModel;
    protected $testUserModel;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->testCommentModel = TestCommentModel::first();
        $this->testUserModel = new TestUserModel;
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]);
    }

    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_mention_users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $app['db']->connection()->getSchemaBuilder()->create('test_mention_comments', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('text');
        });

        foreach (['Keith','Kevin','Ian','Rupert'] as $name) {
            TestUserModel::create(['name' => $name]);
        }

        TestCommentModel::create([
            'user_id' => 1,
            'text' => 'test'
        ]);

        include_once __DIR__.'/../database/migrations/create_mentions_table.php.stub';

        (new \CreateMentionsTable())->up();
    }
}
