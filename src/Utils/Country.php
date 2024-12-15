<?php

namespace App\Utils;

enum Country: string
{
    case Afghanistan = "Afganistán";
    case Albania = "Albania";
    case Germany = "Alemania";
    case Andorra = "Andorra";
    case Angola = "Angola";
    case AntiguaAndBarbuda = "Antigua y Barbuda";
    case SaudiArabia = "Arabia Saudita";
    case Algeria = "Argelia";
    case Argentina = "Argentina";
    case Armenia = "Armenia";
    case Australia = "Australia";
    case Austria = "Austria";
    case Azerbaijan = "Azerbaiyán";
    case Bahamas = "Bahamas";
    case Bangladesh = "Bangladés";
    case Barbados = "Barbados";
    case Bahrain = "Baréin";
    case Belgium = "Bélgica";
    case Belize = "Belice";
    case Benin = "Benín";
    case Belarus = "Bielorrusia";
    case Myanmar = "Birmania";
    case Bolivia = "Bolivia";
    case BosniaAndHerzegovina = "Bosnia y Herzegovina";
    case Botswana = "Botsuana";
    case Brazil = "Brasil";
    case Brunei = "Brunéi";
    case Bulgaria = "Bulgaria";
    case BurkinaFaso = "Burkina Faso";
    case Burundi = "Burundi";
    case Bhutan = "Bután";
    case CapeVerde = "Cabo Verde";
    case Cambodia = "Camboya";
    case Cameroon = "Camerún";
    case Canada = "Canadá";
    case Qatar = "Catar";
    case Chad = "Chad";
    case Chile = "Chile";
    case China = "China";
    case Cyprus = "Chipre";
    case Colombia = "Colombia";
    case Comoros = "Comoras";
    case Congo = "Congo";
    case NorthKorea = "Corea del Norte";
    case SouthKorea = "Corea del Sur";
    case IvoryCoast = "Costa de Marfil";
    case CostaRica = "Costa Rica";
    case Croatia = "Croacia";
    case Cuba = "Cuba";
    case Denmark = "Dinamarca";
    case Dominica = "Dominica";
    case Ecuador = "Ecuador";
    case Egypt = "Egipto";
    case ElSalvador = "El Salvador";
    case UnitedArabEmirates = "Emiratos Árabes Unidos";
    case Eritrea = "Eritrea";
    case Slovakia = "Eslovaquia";
    case Slovenia = "Eslovenia";
    case Spain = "España";
    case UnitedStates = "Estados Unidos";
    case Estonia = "Estonia";
    case Eswatini = "Esuatini";
    case Ethiopia = "Etiopía";
    case Philippines = "Filipinas";
    case Finland = "Finlandia";
    case France = "Francia";
    case Gabon = "Gabón";
    case Gambia = "Gambia";
    case Georgia = "Georgia";
    case Ghana = "Ghana";
    case Grenada = "Granada";
    case Greece = "Grecia";
    case Guatemala = "Guatemala";
    case Guinea = "Guinea";
    case EquatorialGuinea = "Guinea Ecuatorial";
    case GuineaBissau = "Guinea-Bisáu";
    case Guyana = "Guyana";
    case Haiti = "Haití";
    case Honduras = "Honduras";
    case Hungary = "Hungría";
    case India = "India";
    case Indonesia = "Indonesia";
    case Iran = "Irán";
    case Iraq = "Irak";
    case Ireland = "Irlanda";
    case Iceland = "Islandia";
    case Israel = "Israel";
    case Italy = "Italia";
    case Jamaica = "Jamaica";
    case Japan = "Japón";
    case Jordan = "Jordania";
    case Kazakhstan = "Kazajistán";
    case Kenya = "Kenia";
    case Kyrgyzstan = "Kirguistán";
    case Kiribati = "Kiribati";
    case Kosovo = "Kosovo";
    case Kuwait = "Kuwait";
    case Laos = "Laos";
    case Lesotho = "Lesoto";
    case Latvia = "Letonia";
    case Lebanon = "Líbano";
    case Liberia = "Liberia";
    case Libya = "Libia";
    case Liechtenstein = "Liechtenstein";
    case Lithuania = "Lituania";
    case Luxembourg = "Luxemburgo";
    case Madagascar = "Madagascar";
    case Malaysia = "Malasia";
    case Malawi = "Malaui";
    case Maldives = "Maldivas";
    case Mali = "Malí";
    case Malta = "Malta";
    case Morocco = "Marruecos";
    case Mauritius = "Mauricio";
    case Mauritania = "Mauritania";
    case Mexico = "México";
    case Micronesia = "Micronesia";
    case Moldova = "Moldavia";
    case Monaco = "Mónaco";
    case Mongolia = "Mongolia";
    case Montenegro = "Montenegro";
    case Mozambique = "Mozambique";
    case Namibia = "Namibia";
    case Nauru = "Nauru";
    case Nepal = "Nepal";
    case Nicaragua = "Nicaragua";
    case Niger = "Níger";
    case Nigeria = "Nigeria";
    case Norway = "Noruega";
    case NewZealand = "Nueva Zelanda";
    case Oman = "Omán";
    case Netherlands = "Países Bajos";
    case Pakistan = "Pakistán";
    case Palau = "Palaos";
    case Panama = "Panamá";
    case PapuaNewGuinea = "Papúa Nueva Guinea";
    case Paraguay = "Paraguay";
    case Peru = "Perú";
    case Poland = "Polonia";
    case Portugal = "Portugal";
    case UnitedKingdom = "Reino Unido";
    case CentralAfricanRepublic = "República Centroafricana";
    case CzechRepublic = "República Checa";
    case DominicanRepublic = "República Dominicana";
    case Rwanda = "Ruanda";
    case Romania = "Rumanía";
    case Russia = "Rusia";
    case Samoa = "Samoa";
    case SaintKittsAndNevis = "San Cristóbal y Nieves";
    case SanMarino = "San Marino";
    case SaintVincentAndTheGrenadines = "San Vicente y las Granadinas";
    case SaintLucia = "Santa Lucía";
    case SaoTomeAndPrincipe = "Santo Tomé y Príncipe";
    case Senegal = "Senegal";
    case Serbia = "Serbia";
    case Seychelles = "Seychelles";
    case SierraLeone = "Sierra Leona";
    case Singapore = "Singapur";
    case Syria = "Siria";
    case Somalia = "Somalia";
    case SriLanka = "Sri Lanka";
    case SouthAfrica = "Sudáfrica";
    case Sudan = "Sudán";
    case SouthSudan = "Sudán del Sur";
    case Sweden = "Suecia";
    case Switzerland = "Suiza";
    case Suriname = "Surinam";
    case Thailand = "Tailandia";
    case Tanzania = "Tanzania";
    case Tajikistan = "Tayikistán";
    case EastTimor = "Timor Oriental";
    case Togo = "Togo";
    case Tonga = "Tonga";
    case TrinidadAndTobago = "Trinidad y Tobago";
    case Tunisia = "Túnez";
    case Turkmenistan = "Turkmenistán";
    case Turkey = "Turquía";
    case Tuvalu = "Tuvalu";
    case Ukraine = "Ucrania";
    case Uganda = "Uganda";
    case Uruguay = "Uruguay";
    case Uzbekistan = "Uzbekistán";
    case Vanuatu = "Vanuatu";
    case Vatican = "Vaticano";
    case Venezuela = "Venezuela";
    case Vietnam = "Vietnam";
    case Yemen = "Yemen";
    case Zambia = "Zambia";
    case Zimbabwe = "Zimbabue";

    public function toString(): string
    {
        return $this->value;
    }
}