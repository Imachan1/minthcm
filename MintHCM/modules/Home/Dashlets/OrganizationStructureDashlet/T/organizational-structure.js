var config = {
    container: "#organizational-structure",
    // scrollbar: "fancy",
    nodeAlign: "BOTTOM",
    connectors: {
        type: 'step'
    },
    node: {
        HTMLclass: 'nodeExample1',
        collapsable: true
    },
    // animation: {
    //     nodeAnimation: "easeOutBounce",
    //     nodeSpeed: 700,
    //     connectorsAnimation: "bounce",
    //     connectorsSpeed: 700
    // }
},
_ev = { HTMLclass: 'company', image: "logo_evolpe-minimal-09.svg" },
_fe7ad33874a5099ae352785feab92fd0={parent: _ev,text:{name:"Management Board",title:"Marcin Różański", contact: {val: "we@aregreat.com", href: "mailto:we@aregreat.com"}},HTMLclass: 'department', },
test2={parent: _fe7ad33874a5099ae352785feab92fd0,text:{name:"Software Development department",title:"Kierownik Aleksandra Mazurek"},collapsed: true,image: "2.jpg" ,},
test3={parent: _fe7ad33874a5099ae352785feab92fd0,text:{name:"Software Development department",title:"Kierownik Aleksandra Mazurek"},collapsed: true,image: "2.jpg" ,},


_4ad74fd8236adfb21b863cb54b05850a={parent: _fe7ad33874a5099ae352785feab92fd0,text:{name:"Software Development department",title:"Kierownik Aleksandra Mazurek"},HTMLclass: 'department' ,collapsed: true,},

_4ad74fd8236adfb21b863cb54b05850t={parent: _4ad74fd8236adfb21b863cb54b05850a,text:{name:"Software Development department Team leader ",title:"Aleksandra Mazurek"} ,image: "2.jpg" },

_7b59b958e99c45aaf4b7d44d463ce4bb={parent: _fe7ad33874a5099ae352785feab92fd0,text:{name:"Sales & Marketing department",title:"Marcin Różański"},HTMLclass: 'department' ,collapsed: true,},
_a9eb6d584e6e5b7c622f42d3ac7d2ef8={parent: _fe7ad33874a5099ae352785feab92fd0,text:{name:"Business Support department",title:"Kierownik Marcin Różański"},HTMLclass: 'department' ,collapsed: true, },

_a9eb6d584e6e5b7c622f42d3ac7d2ef7={parent: _a9eb6d584e6e5b7c622f42d3ac7d2ef8,text:{name:"Business Support department",title:"Marcin Różański"} ,image: "2.jpg" },


_44494baf2a517539dd1d90c5e5dcaa90={parent: _fe7ad33874a5099ae352785feab92fd0,text:{name:"IT Business Analysis department",title:"Magdalena Ziębińska"},HTMLclass: 'department' },
_396c8b014c3e1b0c2a2c5ce55faceed9={parent: _a9eb6d584e6e5b7c622f42d3ac7d2ef7,text:{name:"HR & Office team",title:"Marcin Różański"},HTMLclass: 'team' ,collapsed: true,image: "2.jpg" },
_a2d75ae596ff0e82b7c897252fe1d878={parent: _7b59b958e99c45aaf4b7d44d463ce4bb,text:{name:"Marketing team",title:"Marcin Różański"},HTMLclass: 'team',collapsed: true,image: "2.jpg"  },
_8e0ff4a2d63ee7c9dd36d53fe751cd42={parent: _7b59b958e99c45aaf4b7d44d463ce4bb,text:{name:"Sales team",title:"Sławomir Wnuk"},HTMLclass: 'team',collapsed: true,image: "2.jpg"  },

_746168db1cd6a0fb02a528451902bd71={parent: _4ad74fd8236adfb21b863cb54b05850t,text:{name:"Software Development Team Leader",title:"Michał Nowacki"} ,image: "2.jpg" },
_ef40005e2a7642b73790b10535f8b94c={parent: _a2d75ae596ff0e82b7c897252fe1d878,text:{name:"Marketing Team Leader",title:"Joanna Radecka"} ,image: "2.jpg" },
_7ca0f2714274417c7f42ff9cba17d192={parent: _746168db1cd6a0fb02a528451902bd71,text:{name:"PHP/JS Developer",title:"Dawid Brezwan"} ,image: "2.jpg" },
_a0556cd1d8b76e4044d08a453c592518={parent: _44494baf2a517539dd1d90c5e5dcaa90,text:{name:"IT Business Analysis Team Leader",title:"Anna Łakoma"} ,image: "2.jpg" },
_0e1c9877166469b19f7b0c293fb3cf78={parent: _ef40005e2a7642b73790b10535f8b94c,text:{name:"Lead Generation & Social Media Specialist",title:"Sebastian Osses"} ,image: "2.jpg" },
_a80e78ddd4bba7d6583d48e802b13536={parent: _a9eb6d584e6e5b7c622f42d3ac7d2ef7,text:{name:"IT Administrator",title:"Szymon Nitka"} ,image: "2.jpg" },
_238e0bf8be21a0972a6c7ea302e2198c={parent: _746168db1cd6a0fb02a528451902bd71,text:{name:"Senior PHP/JS Developer",title:"Tomasz Szykuła"} ,image: "2.jpg" },
_4c13e5d18754e4b0b5746f53089bd092={parent: _8e0ff4a2d63ee7c9dd36d53fe751cd42,text:{name:"IT Account Manager",title:"Janusz Sobczak"} ,image: "2.jpg" },
_c2e3aaf086713e71ff586b32337ffc13={parent: _a0556cd1d8b76e4044d08a453c592518,text:{name:"IT Business Analyst",title:"Bartosz Burzyński"} ,image: "2.jpg" },
_0be2288ec7f64b1ad53b8824dc2615ce={parent: _746168db1cd6a0fb02a528451902bd71,text:{name:"Senior PHP/JS Developer",title:"Łukasz Kończak"} ,image: "2.jpg" },
_1d25e6888365afd8d9a05b3315d9a5cf={parent: _396c8b014c3e1b0c2a2c5ce55faceed9,text:{name:"HR & Employer Branding Specialist",title:"Marta Mazurek"} ,image: "2.jpg" },
_15924f2c06665ebc274c253038372440={parent: _8e0ff4a2d63ee7c9dd36d53fe751cd42,text:{name:"Sales Support Specialist",title:"Kamil Ograbisz"} ,image: "2.jpg" },
_6dc6772ecf207437a5f85b13ad7515bd={parent: _4ad74fd8236adfb21b863cb54b05850t,text:{name:"Junior Tester",title:"Ewelina Milecka"} ,image: "2.jpg" },
_39f9210a97e5613eb0292f52b934a594={parent: _746168db1cd6a0fb02a528451902bd71,text:{name:"PHP/JS Developer",title:"Marek Domagalski"} ,image: "2.jpg" },
_5a7714e20e30f6af66dcf7538a60604c={parent: _ef40005e2a7642b73790b10535f8b94c,text:{name:"Web Developer",title:"Michał Dudziński"} ,image: "2.jpg" },
_51b12b557286686538e5854bf7c1c8fb={parent: _396c8b014c3e1b0c2a2c5ce55faceed9,text:{name:"HR Business Partner",title:"Jakub Zieliński"} ,image: "2.jpg" },
_9bce826eaeb9345b05d665a88a36998a={parent: _396c8b014c3e1b0c2a2c5ce55faceed9,text:{name:"Office Manager",title:"Katarzyna Myszka"} ,image: "2.jpg" },
_16c2e21090a3fdfa7b6e17016d0d16e3={parent: _7ca0f2714274417c7f42ff9cba17d192,text:{name:"Junior PHP/JS Developer",title:"Marcin Gawronek"} ,image: "2.jpg" },
_bcb1c9d2e093bb0895147dae25c10c0a={parent: _4ad74fd8236adfb21b863cb54b05850t,text:{name:"Junior Tester",title:"Michał Michalak"} ,image: "2.jpg" },
_4c9ee2846a79e293e5f500250666b2aa={parent: _746168db1cd6a0fb02a528451902bd71,text:{name:"PHP/JS Developer",title:"Szymon Rydza"} ,image: "2.jpg" },
_fb957a4aafbe820b8ee0b84498364e69={parent: _8e0ff4a2d63ee7c9dd36d53fe751cd42,text:{name:"Presales Engineer",title:"Maciej Jankiewicz"} ,image: "2.jpg" },
_385a2f938cd11150e539f4995e4e8061={parent: _8e0ff4a2d63ee7c9dd36d53fe751cd42,text:{name:"Sales Support Specialist",title:"Kamil Ograbisz"} ,image: "2.jpg" },



chart_config = [
    config,
    _ev,
    _4ad74fd8236adfb21b863cb54b05850a,
    _396c8b014c3e1b0c2a2c5ce55faceed9,
    _7b59b958e99c45aaf4b7d44d463ce4bb,
    _a9eb6d584e6e5b7c622f42d3ac7d2ef8,
    _44494baf2a517539dd1d90c5e5dcaa90,
    _a2d75ae596ff0e82b7c897252fe1d878,
    _8e0ff4a2d63ee7c9dd36d53fe751cd42,
    _fe7ad33874a5099ae352785feab92fd0,
    _4ad74fd8236adfb21b863cb54b05850t,
    _a9eb6d584e6e5b7c622f42d3ac7d2ef7,
    test2,
    test3,


    _746168db1cd6a0fb02a528451902bd71,
    _a0556cd1d8b76e4044d08a453c592518,
    _7ca0f2714274417c7f42ff9cba17d192,
    _ef40005e2a7642b73790b10535f8b94c,
    _385a2f938cd11150e539f4995e4e8061,
    _fb957a4aafbe820b8ee0b84498364e69,
    _4c9ee2846a79e293e5f500250666b2aa,
    _bcb1c9d2e093bb0895147dae25c10c0a,
    _16c2e21090a3fdfa7b6e17016d0d16e3,
    _9bce826eaeb9345b05d665a88a36998a,
    _51b12b557286686538e5854bf7c1c8fb,
    _5a7714e20e30f6af66dcf7538a60604c,
    _39f9210a97e5613eb0292f52b934a594,
    _6dc6772ecf207437a5f85b13ad7515bd,
    _15924f2c06665ebc274c253038372440,
    _1d25e6888365afd8d9a05b3315d9a5cf,
    _0be2288ec7f64b1ad53b8824dc2615ce,
    _c2e3aaf086713e71ff586b32337ffc13,
    _4c13e5d18754e4b0b5746f53089bd092,
    _238e0bf8be21a0972a6c7ea302e2198c,
    _a80e78ddd4bba7d6583d48e802b13536,
    _0e1c9877166469b19f7b0c293fb3cf78,


];


