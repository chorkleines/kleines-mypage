import { config, library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

import {
  faBars,
  faUser,
  faHome,
  faUsers,
  faWallet,
  faMoneyCheckDollar,
  faYenSign,
  faCircleCheck,
  faEllipsis,
  faChevronLeft,
  faChevronRight,
  faSort,
  faSortUp,
  faSortDown,
  faPenToSquare,
} from "@fortawesome/free-solid-svg-icons";

export default defineNuxtPlugin((nuxtApp) => {
  config.autoAddCss = false;
  library.add(
    faBars,
    faUser,
    faHome,
    faUsers,
    faWallet,
    faMoneyCheckDollar,
    faYenSign,
    faCircleCheck,
    faEllipsis,
    faChevronLeft,
    faChevronRight,
    faSort,
    faSortUp,
    faSortDown,
    faPenToSquare,
  );
  nuxtApp.vueApp.component("font-awesome-icon", FontAwesomeIcon);
});
