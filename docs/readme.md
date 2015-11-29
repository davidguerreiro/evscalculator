#Specification

This contains all the details about EVs Calculator, including its behaviour and elements to account for.

- [Database Structure](database.md): The database tables, fields, descriptions and relationships.
- [User journey](journey.md): The technical behaviour specification.
- [Adding EVs](adding-evs.md): All about hordes, vitamins and manual inputs.


#Some stuff we must know

##Evs

###Introduction

Effort Values, or EVs for short, can have a huge impact on your Pokémon and the battles they participate in. EVs will let Swampert survive Hidden Power Grass, or will let Skarmory outspeed Magneton, and many other things. Having a proper EV spread (distribution of EV points on a Pokémon) can mean the difference between a win and a loss.

A total of **255 EV points can be put onto any one stat**, and a total of **510 points total can be given to any Pokémon**. However, it is important to note that a stat will only increase after every four EV points. In other words, putting 12 EVs and putting 15 EVs both result in the same thing: a 3 point increase to the stat.

* Pokemon gain Evs whenever the gain experience; if you're not starting with a freshly hatched Pokémon, you will probably need to reset its EVs with a Reset Bag first.
* Pokerus doubles the EVs gained per Pokémon defeated
* Macho Brace doubles the EVs gained per Pokémon defeated
* Power items add +4 in a specific stat to the Evs gained per Pokemon defeated
* With Exp. Share ON, all Pokemon in the party gain EVs

###Modifying Ev Gain

Basically, we can modify the way we gain EVs altering our Pokémon's status or attaching a boost item:

* **Pokérus** : Doubles EVs gained, can be combined with Macho Brace and Power Items
* ![Macho Brace](http://vignette2.wikia.nocookie.net/es.pokemon/images/d/dc/Brazal_firme.png/revision/latest?cb=20090701100750)**Macho Brace** : Doubles Evs gained, halves the holder's Speed, can be combined with Pokérus
* **Power Items** : give 4 additional EV points to the corresponding stat, halves holder's Speed, can be combined with Pokérus
	* ![Power Weight](http://vignette4.wikia.nocookie.net/es.pokemon/images/5/50/Pesa_recia.png/revision/latest?cb=20091010155052&format=webp) **Power Weight** : Adds +4 EVs in HP
	* ![Power Brace](http://vignette3.wikia.nocookie.net/es.pokemon/images/9/9d/Brazal_recio.png/revision/latest?cb=20090701200903&format=webp) **Power Brace** : Adds +4 Evs in Attack
	* ![Power Belt](http://vignette3.wikia.nocookie.net/es.pokemon/images/d/d4/Cinto_recio.png/revision/latest?cb=20090701202447&format=webp) **Power Belt : Adds +4 EVs in Defense
	* ![Power Lens](http://vignette4.wikia.nocookie.net/es.pokemon/images/9/91/Lente_recia.png/revision/latest?cb=20090702125257&format=webp) **Power Lens** : Adds +4 EVs in Special Attack
	* ![Power Band](http://vignette2.wikia.nocookie.net/es.pokemon/images/7/76/Banda_recia.png/revision/latest?cb=20090701200836) **Power Band** : Adds +4 EVs in Special Defense
	* ![Power Anklet](http://vignette1.wikia.nocookie.net/es.pokemon/images/1/1f/Franja_recia.png/revision/latest?cb=20091010154647&format=webp) **Power Anklet** : Adds +4 EVs in Speed

###Calculations for EVs per Pokemon

Every Pokémon can give us 1, 2 or 3 EVs depending of the specie. For example, every rattata gives us 1 EV point and every Raticate gives us 2 EVs points.

####Base 1 point: 

* Pokérus / Macho Brace : 2 EVs
* Pokérus + Macho Brace : 4 EVs
* Pokérus + Power Item : 10 EVs

####Base 2 points:

* Pokérus / Macho Brace : 4 EVs
* Pokérus + Macho Brace : 8 EVs
* Pokérus + Power Item : 12 EVs

####Base 3 points:

* Pokérus / Macho Brace : 6 EVs
* Pokérus + Macho Brace : 12 EVs
* Pokérus + Power Item : 14 EVs

**The final result has to be multiplyed by 5 for horde encounters**.
