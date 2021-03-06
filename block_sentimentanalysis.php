<?php
    // This file is part of Moodle - http://moodle.org/
    //
    // Moodle is free software: you can redistribute it and/or modify
    // it under the terms of the GNU General Public License as published by
    // the Free Software Foundation, either version 3 of the License, or
    // (at your option) any later version.
    //
    // Moodle is distributed in the hope that it will be useful,
    // but WITHOUT ANY WARRANTY; without even the implied warranty of
    // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    // GNU General Public License for more details.
    //
    // You should have received a copy of the GNU General Public License
    // along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

    /**
     * block_sentimentanalysis
     *
     * @author      Kara Beason <beasonke@appstate.edu>
     * @copyright   (c) 2019 Appalachian State Universtiy, Boone, NC
     * @license     GNU General Public License version 3
     * @package     block_sentimentanalysis
     */

    require_once(__DIR__ . '/lib.php');

    defined('MOODLE_INTERNAL') || die();

    /**
     * Block plugin
     */

    class block_sentimentanalysis extends block_base {

        /**
         * Called by the parent class constructor
         */
        public function init() {
            $this->title = get_string('sentimentanalysis', 'block_sentimentanalysis');
        }

        /**
         * {@inheritDoc}
         * @see block_base::get_content()
         */
        public function get_content() {

            global $COURSE;

            // Check current user's capabilities.
            // Only admin user or instructor for this course should view this block.
            if (!has_capability('moodle/course:manageactivities', context_course::instance($COURSE->id)))
            {
                // Display nothing.
                return;
            }

            // This method called initially only to see if content
            // exists, then a second time to actually emit it.
            if ($this->content !== null) {
                return $this->content;
            }

            $this->content =  new stdClass;

            $block_config = get_config('block_sentimentanalysis');

            // If block INSTANCE assignment list has not been configured, nothing to run.
            if (!$this->config)
            {
                $this->content->text = get_string("noconfigprompt", "block_sentimentanalysis");
            }
            else
            {
                // Display button that queues the sentiment analysis task via execute_task.php
                //  will be displayed.
                $executetask = new moodle_url('/blocks/sentimentanalysis/execute_task.php',
                    array('id' => $this->instance->id));

                $this->content->text = '<div algin = "center" ><a href="'.$executetask.'" class="btn btn-primary">'.get_string('executetask', 'block_sentimentanalysis').'</a></div>';
            }

            return $this->content;
        }

        /**
         * {@inheritDoc}
         * @see block_base::has_config()
         */
        function has_config() {
            return true;
        }

        //method that returns an associative array of attribute names and values, allowing us to change defaut behavior
        //  of block display
        public function html_attributes()
        {
            $attributes = parent::html_attributes(); // get default values
            $attributes['class'] .= ' block_' . $this->name(); // append our class to class attribute
            return $attributes;
        }

        // this block can only be added to a course view.
        public function applicable_formats() {
            return array(
                'course-view' => true);
        }
    }
