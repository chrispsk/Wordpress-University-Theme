import "../css/style.css"
import $ from "jquery"
// Our modules / classes
import MobileMenu from "./modules/MobileMenu"
import HeroSlider from "./modules/HeroSlider"
import GoogleMap from "./modules/GoogleMap"
import Search from "./modules/Search.js"

// Instantiate a new object using our modules / classes
const mobileMenu = new MobileMenu()
const heroSlider = new HeroSlider()
const googleMap = new GoogleMap()
const search = new Search()
console.log("baaaaaaa");
// Allow new JS and CSS to load in browser without a traditional page refresh
if (module.hot) {
  module.hot.accept()
}
