#User journey

Description of the options on the site and what each of them do.


##Use case
We will be using the use case described at the introduction as it's the most common.

We have a trainer, who has a PoKémon with 0 EVs on each stat and wants to target 2 specific stats during its training and then leave the remaining EVs for a third, less important stat.

To gain EVs he will combat hordes and maybe use vitamins/berrys.
To improve the gain from combats the trainer will use Pokerus, the Macho Brace and Power Items (see [adding EVs for details](adding-evs.md)).


## Landing page
Now that we know what the trainer wants, let's get him training as soon as possible.
What we need to know is:

* What stats he wants to train?
* Does he have the Pokerus?
* Does he have the Macho Brace assigned?

Let's start getting to know the stats he wants to train by asking the target EV count.
The landing page will have six buttons, one per stat.

HP | Attack | Defense | Sp. Attack | Sp. Defense | Speed

When you click on one of these buttons, this happens:

1. The button becomes a combo of button and input field.
* The focus changes from the button to the input.
* The input gains a default value depending on the rest of the button's state.

The default value of the inputs. We will consider the last stat to be the least important, so all EVs shall be split between the other selected ones first and then round up to multiples of 4. The remaining will go to the last selected stat.

* If this is the first or second input opened: 252 (maximum)
* If this is the third input opened: Remaining EVs (510 - first.value - second.value)
* If you press on a fourth button: All stats should have EVEN numbers, multiples of 4 but the last one, with the remaining difference.


When you click on a button that is already open:

* The field dissapears and it becomes just a button.
* The value of the hidden field becomes zero.


