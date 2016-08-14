<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


        Schema::create('locations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('short_name')->unique();
            $table->string('long_name');
            $table->boolean('is_archive_location')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });


        Schema::create('roles', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('short_name');
            $table->string('long_name');
            $table->boolean('is_developer')->default(FALSE);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('short_name');
            $table->string('long_name');
            $table->string('url');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_admin_page')->default(false);
            $table->integer('order')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('page_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('role_id')->unsigned()->nullable();
            $table->foreign('role_id')->references('id')
                ->on('roles')->onDelete('cascade');

            $table->integer('page_id')->unsigned()->nullable();
            $table->foreign('page_id')->references('id')
                ->on('pages')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('abhyasiid');
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->boolean('is_developer')->default(false);
            $table->integer('role')->unsigned()->nullable();
            $table->integer("location")->unsigned()->default(1);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign("location")->references('id')->on('locations');
            $table->foreign("role")->references('id')->on('roles');
        });

        Schema::create('artefact_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('artefact_type_short');
            $table->string('artefact_type_long');
            $table->string('artefact_description');
            $table->boolean('active')->default(TRUE);
            $table->timestamps();
        });


        Schema::create('conditional_reports_segments',function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('artefact_type_id')->unsigned();
            $table->string('segment_name');
            $table->string('segment_title');
            $table->boolean('active')->default(TRUE);
            $table->timestamps();

            $table->foreign('artefact_type_id')->references('id')->on('artefact_types');
        });

        Schema::create('conditional_reports',function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('conditional_reports_segments_id')->unsigned();
            $table->string("conditional_report_name");
            $table->string("conditional_report_title");
            $table->string("conditional_report_html_type");
            $table->boolean("conditional_report_pick_flag")->default(false);
            $table->json("conditional_report_pick_data")->nullable();
            $table->boolean('active')->default(TRUE);
            $table->timestamps();

            $table->foreign('conditional_reports_segments_id')->references('id')->on('conditional_reports_segments');
        });


        Schema::create('artefacts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('old_artefact_id');
            $table->integer("parent_id")->nullable();
            $table->string("artefact_name")->nullable();
            $table->integer('location')->unsigned();
            $table->integer('artefact_type')->unsigned();
            $table->json("artefact_values")->nullable();
            $table->integer("user_id")->unsigned();
            $table->boolean("active")->default(true);
            $table->timestamps();


            $table->foreign("location")->references('id')->on('locations')->onDelete('cascade');
            $table->foreign("parent_id")->references('id')->on('artefacts')->onDelete('cascade');
            $table->foreign("user_id")->references('id')->on('users')->onDelete('cascade');
            $table->foreign("artefact_type")->references('id')->on('artefact_types')->onDelete('cascade');
        });

        Schema::create('scheduled_maintenences',function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('artefact_id')->unsigned();
            $table->string('maintenence_type');
            $table->boolean('active')->default(TRUE);
            $table->timestamps();

            $table->foreign('artefact_id')->references('id')->on('artefacts');
        });

        Schema::create('scheduled_maintenence_dates',function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('scheduled_maintenence_id')->unsigned();
            $table->date('maintenence_date');
            $table->boolean('is_completed')->default(false);
            $table->json('conditional_report_result_data')->nullable();
            $table->boolean('active')->default(TRUE);
            $table->timestamps();
            $table->foreign('scheduled_maintenence_id')->references('id')->on('scheduled_maintenences');
        });


        Schema::create('artefact_type_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('artefact_type_id')->unsigned()->nullable();
            $table->foreign('artefact_type_id')->references('id')
                ->on('artefact_types')->onDelete('cascade');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('cico', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer("artefact_id")->unsigned();
            $table->integer("user_id")->unsigned();
            $table->boolean('check_out_status')->default('true');
            $table->string("check_out_description")->nullable();
            $table->string("check_in_description")->nullable();
            $table->string("remarks")->default('No Remarks Found');
            $table->timestamps();

            $table->foreign("user_id")->references('id')->on('users')->onDelete('cascade');
            $table->foreign("artefact_id")->references('id')->on('artefacts')->onDelete('cascade');

        });

        Schema::create('artefact_type_attributes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer("artefact_type_id")->unsigned();
            $table->string("attribute_title");
            $table->string("html_type");
            $table->boolean("is_searchable")->default(true);
            $table->boolean("pick_flag")->default(false);
            $table->boolean("active")->default(true);
            $table->timestamps();
            $table->foreign("artefact_type_id")->references('id')->on('artefact_types')->onDelete('cascade');
        });

        Schema::create('pick_data', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer("attribute_id")->unsigned();
            $table->string("pick_data_value");
            $table->boolean('active')->default(TRUE);
            $table->timestamps();

            $table->foreign("attribute_id")->references('id')->on('artefact_type_attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

        Schema::drop("cico");
        Schema::drop("artefact_type_user");
        Schema::drop("scheduled_maintenence_dates");
        Schema::drop("scheduled_maintenences");
        Schema::drop("artefacts");
        Schema::drop("pick_data");
        Schema::drop("artefact_type_attributes");

        Schema::drop("conditional_reports");
        Schema::drop("conditional_reports_segments");

        Schema::drop("artefact_types");
        Schema::drop("page_role");
        Schema::drop("pages");
        Schema::drop("users");
        Schema::drop("roles");
        Schema::drop("locations");
    }
}
