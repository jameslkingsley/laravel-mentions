<?php

namespace Kingsley\Mentions\Test;

use DB;
use Illuminate\Database\Schema\Blueprint;
use Kingsley\Mentions\Test\TestMiddleware;
use Orchestra\Testbench\TestCase as Orchestra;
use Orchestra\Testbench\Traits as OrchestraTrait;

abstract class TestCase extends Orchestra
{
    use OrchestraTrait\WithLaravelMigrations;

    protected $testCommentModel;
    protected $testUserModel;

    public function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'sqlite']);
        $this->setUpDatabase($this->app);
        $this->setUpMiddleware($this->app);

        $this->testCommentModel = TestCommentModel::first();
        $this->testUserModel = TestUserModel::first();
    }

    protected function getPackageProviders($app)
    {
        return [
            'Kingsley\Mentions\MentionServiceProvider',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('app.debug', true);

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]);

        $app['config']->set('mentions', [
            'middleware' => null,
            'pools' => [
                'users' => [
                    'model' => 'Kingsley\Mentions\Test\TestUserModel',
                    'resource' => null,
                    'filter' => null,
                    'column' => 'name',
                    'notification' => 'Kingsley\Mentions\Test\TestNotifiedModel',
                    'auto_notify' => false
                ]
            ]
        ]);
    }

    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_mention_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
        });

        $app['db']->connection()->getSchemaBuilder()->create('test_mention_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('text', 30);
        });

        $app['db']->connection()->getSchemaBuilder()->create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        foreach (['Keith', 'Kevin', 'Ian', 'Rupert'] as $name) {
            DB::table('test_mention_users')->insert([
                'name' => $name
            ]);
        }

        DB::table('test_mention_comments')->insert([
            'user_id' => 1,
            'text' => 'test'
        ]);

        include_once __DIR__ . '/../database/migrations/create_mentions_table.php.stub';

        (new \CreateMentionsTable())->up();
    }

    protected function setUpMiddleware($app)
    {
        $app['router']->aliasMiddleware('test-middleware', TestMiddleware::class);
    }
}
