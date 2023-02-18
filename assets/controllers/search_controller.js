import {Controller} from "@hotwired/stimulus"
import axios from "axios";

export default class extends Controller {

	static targets = [
		'species',
		'search',
		'autocomplete_list',
		'links'
	]

	async search() {
		const searchValue = this.speciesTarget.value
		const result = await axios.get('/api/species/' + searchValue)
		const data = result.data

		if (data)
			await this.autoComplete(data)
	}

	async autoComplete(data) {

		this.autocomplete_listTarget.innerHTML = ''
		data.forEach(element => {
			let li = document.createElement('li')
			li.textContent = element.vernacularName + " (" + element.scientificName + ")"
			li.setAttribute('data-id', element.id)
			li.addEventListener('click', () => {
				this.select(element)
			})
			this.autocomplete_listTarget.append(li)
		})
	}

	emptyResults() {
		this.autocomplete_listTarget.innerHTML = ''
	}

	async select(species) {
		this.emptyResults()
		this.linksTarget.innerHTML = ""
		let sightingButton = document.createElement('a')
		sightingButton.className = 'button primary'
		sightingButton.href = '/sighting/new/' + species.id
		sightingButton.textContent = 'Registrera glutt'
		this.linksTarget.append(sightingButton)
		let speciesButton = document.createElement('a')
		speciesButton.className = 'button primary'
		speciesButton.href = '/species/' + species.id
		speciesButton.textContent = 'Visa fågel'
		this.linksTarget.append(speciesButton)
	}
}
