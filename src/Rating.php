<?php

namespace oddEvan\Periodical;

enum Rating: int {
	case General = 0;
	case Teen = 1;
	case Mature = 2;
	case Adult = 3;

	public function description(): string {
		switch($this) {
			case Rating::General: return 'General';
			case Rating::Teen: return 'Teen';
			case Rating::Mature: return 'Mature';
			case Rating::Adult: return 'Adult';
		}
	}
}