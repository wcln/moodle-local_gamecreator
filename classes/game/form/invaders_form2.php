<?php

namespace local_gamecreator\game\form;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');

use moodleform;

class invaders_form2 extends moodleform {
	protected function definition() {
		global $CFG;

		$mform = $this->_form;

		for ($level = 0; $level <= $this->_customdata->numlevels; $level++) {

			// header
			$mform->addElement('header', "levelheader$level", get_string('level', 'local_gamecreator')." ".($level+1));

			for ($question = 0; $question <= $this->_customdata->numquestions; $question++) {

				// question
				$mform->addElement('text', "q_".$level.$question, get_string('question', 'local_gamecreator'));
				$mform->setType("q_".$level.$question, PARAM_TEXT);

				// answer
				$mform->addElement('text', "a_".$level.$question, get_string('answer', 'local_gamecreator'));
				$mform->setType("a_".$level.$question, PARAM_TEXT);

				// options
				for ($i = 0; $i < 3; $i++) {
					$mform->addElement('text', "o_".$level.$question.$i, get_string('option', 'local_gamecreator'));
					$mform->setType("o_".$level.$question.$i, PARAM_TEXT);
				}
			}
		}

		$mform->addElement('hidden', 'numlevels', $this->_customdata->numlevels);
		$mform->setType('numlevels', PARAM_RAW);
		$mform->addElement('hidden', 'numquestions', $this->_customdata->numquestions);
		$mform->setType('numquestions', PARAM_RAW);
		$mform->addElement('hidden', 'title', $this->_customdata->gametitle);
		$mform->setType('title', PARAM_RAW);
		$this->add_action_buttons(true, get_string('creategame', 'local_gamecreator'));

	}

	public function validation($data, $files) {

		$errors = parent::validation($data, $files);

		$numlevels = $data['numlevels'];
		$numquestions = $data['numquestions'];

		for ($i = 0; $i <= $numlevels; $i++) {
			for ($j = 0; $j <= $numquestions; $j++) {
				if (empty($data['q_'.$i.$j])) {
					$errors['q_'.$i.$j] = get_string('missing', 'local_gamecreator');
				}
				if (empty($data['a_'.$i.$j])) {
					$errors['a_'.$i.$j] = get_string('missing', 'local_gamecreator');
				}

				if (strlen($data['q_'.$i.$j]) > 15) {
					$errors['q_'.$i.$j] = get_string('toolong', 'local_gamecreator');
				}

				if (strlen($data['a_'.$i.$j]) > 6) {
					$errors['a_'.$i.$j] = get_string('toolong', 'local_gamecreator');
				}

				for ($k = 0; $k < 3; $k++) {
					if (empty($data['o_'.$i.$j.$k])) {
						$errors['o_'.$i.$j.$k] = get_string('missing', 'local_gamecreator');
					}

					if ($data['o_'.$i.$j.$k] == $data['a_'.$i.$j]) {
						$errors['o_'.$i.$j.$k] = get_string('answerinoptions', 'local_gamecreator');
					}

					if (strlen($data['o_'.$i.$j.$k]) > 6) {
						$errors['o_'.$i.$j.$k] = get_string('toolong', 'local_gamecreator');
					}

					for ($l = 0; $l < 3; $l++) {
						if ($l != $k) {
							if ($data['o_'.$i.$j.$k] == $data['o_'.$i.$j.$l]) {
								$errors['o_'.$i.$j.$k] = get_string('duplicate', 'local_gamecreator');
								$errors['o_'.$i.$j.$l] = get_string('duplicate', 'local_gamecreator');
							}
						}
					}

				}
			}
		}


		return $errors;
	}
}
