import {Controller} from "@hotwired/stimulus";
import axios from "axios";

export default class extends Controller {
	static values = {
		'id': String
	}
	static targets = [
		'id',
		'artfaktaApi',
		'characteristics',
		'ecology'
	]

	connect() {
		this.load()
	}

	async load() {
		let result = await axios.get('/api/artfakta/' + this.idValue)

		const data = result.data[0].speciesData

		if (data.speciesFactText.characteristic)
			this.characteristicsTarget.innerHTML = '<h3>Drag</h3>' + data.speciesFactText.characteristic
		if (data.speciesFactText.ecology)
			this.ecologyTarget.innerHTML = '<h3>Ekologi</h3>' + data.speciesFactText.ecology
	}


}