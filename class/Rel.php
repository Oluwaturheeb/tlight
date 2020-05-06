<?php

class Rel extends Db {

	private $_pre;

	/*
	option = [restrict, cascade, set null]

	*/

	public function table ($tab) {
		// borrowed this var!
		$this->_pre = $tab;
		parent::table($tab);
		return $this;
	}

	public function set ($tab = [], $link = [], $option = []) {
		$opt = "";

		if (!empty($option))
			foreach ($option as $key => $value) {
				$val = explode("-", $value);
				if ($val[1] == "null") {
					$opt .= "on {$val[0]} set null ";
				} else {
					$opt .= "on {$val[0]} {$val[1]} ";
				}
			} 
		else
			$opt = "on update restrict on delete restrict";

	 	$alt = "alter table {$tab[1]} add constraint fk_{$tab[0]}_{$tab[1]} foreign key({$link[1]}) references {$tab[0]}({$link[0]}) {$opt}";

		$this->customQuery($alt);

		if ($this->error()) {
		 	return "Note ** Relationship has been established between ". implode(" and ", $tab) . "!";
		} else {
		 	$tab = implode("_", $tab);
		 	$link = implode(" = ", $link);
		 	$this->table("relation");
			$this->add(["tab", "rel"], [$tab, $link])->res();

			return "Relationship has been created!";
		}
	}

	public function fetch ($col, $type = "") {
		$this->customQuery("select rel from relation where tab = ?", [implode("_", $this->_pre)]);
		$this->_pre = $this->res(1)->rel;

		$this->get($col)->use("join", $type)->match([explode(" = ", $this->_pre)]);
		return $this;
	}
}