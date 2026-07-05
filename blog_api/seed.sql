-- =============================================================================
-- Inkwell Blog Reader — Seed Data
-- Populates the posts table with the sample articles used by the front end.
-- Run after schema.sql.
-- =============================================================================

USE inkwell_blog;

INSERT INTO posts (id, title, author, date_posted, last_edited, reading_time, category, thumbnail, excerpt, content) VALUES
(1, 'Designing Interfaces That Disappear', 'Maren Solberg', '2026-06-02', '2026-06-04', 6, 'Design', 'https://images.unsplash.com/photo-1559028012-481c04fa702d?w=1200&q=80', 'Good interfaces get out of the way. Here\'s what that actually means in practice, beyond the slogan.', '<p>The best compliment an interface can receive is silence. Not the silence of neglect, but the silence of someone who finished a task without ever noticing the tool they used to do it. That\'s a harder target than it sounds, because disappearing is not the same as doing less.</p>

      <h3>Friction has a job</h3>
      <p>Every extra click, every confirmation dialog, every animation delay is a decision, whether or not anyone made it on purpose. The goal isn\'t to remove all of it. It\'s to keep the friction that protects people from mistakes, and cut the friction that only protects the system from ambiguity.</p>

      <blockquote>"An interface should feel like a good editor: present exactly when the writer needs a second opinion, invisible the rest of the time."</blockquote>

      <p>That distinction matters most at the edges of a product, in empty states, error messages, and confirmations, because that\'s where people are least sure of themselves and most likely to notice the tool at all.</p>

      <h3>Three habits worth keeping</h3>
      <ul>
        <li>Name controls after what people are trying to do, not after how the system implements it.</li>
        <li>Keep the vocabulary of an action consistent from the button to the confirmation to the result.</li>
        <li>Treat every empty screen as an invitation, not a dead end.</li>
      </ul>

      <p>None of this is glamorous. It rarely shows up in a portfolio piece. But it\'s the difference between a product people tolerate and one they forget they\'re using at all, which, for an interface, is the highest praise there is.</p>'),
(2, 'The Quiet Cost of Context Switching', 'Femi Adeyemi', '2026-05-27', '2026-05-27', 5, 'Productivity', 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=1200&q=80', 'Every tab you open borrows a little focus from the one you just closed. Here\'s how that debt compounds.', '<p>It\'s tempting to measure a workday by the number of things it touched: messages answered, tickets closed, tabs opened and closed again. But attention doesn\'t reset instantly when you switch tasks. Some part of it stays behind, working on the problem you just left.</p>

      <h3>The switch tax</h3>
      <p>Researchers call this "attention residue." You start a new task, but a portion of your working memory keeps rehearsing the old one, and that portion isn\'t available for the task in front of you. The result is that every switch costs more than the few seconds it takes to open a new window.</p>

      <p>The cost isn\'t linear, either. Two context switches an hour barely register. Ten, and the day starts to feel like it evaporated even though you were "busy" the whole time.</p>

      <h3>What actually helps</h3>
      <ol>
        <li>Batch similar tasks into blocks instead of interleaving them.</li>
        <li>Write down the next step before switching away from something unfinished, so your brain doesn\'t have to hold onto it.</li>
        <li>Protect at least one block a day with no inbound notifications at all.</li>
      </ol>

      <p>None of these eliminate switching, work rarely allows for that. But they shrink the residue each switch leaves behind, which over a week adds up to hours you\'d otherwise lose without ever noticing where they went.</p>'),
(3, 'What Static Typing Actually Buys You', 'Priya Raman', '2026-05-19', '2026-05-21', 8, 'Engineering', 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&q=80', 'Not fewer bugs. Something more specific, and more useful, than that.', '<p>Ask why a team chose a statically typed language and you\'ll usually hear "fewer bugs." That\'s true, but vague enough to be unpersuasive to anyone who hasn\'t already been burned. The more precise answer is about where a certain class of bug gets caught, not whether it exists at all.</p>

      <h3>Shifting the moment of failure</h3>
      <p>A type system doesn\'t stop you from writing a wrong idea. It stops you from shipping the specific kind of wrong idea where a value doesn\'t match the shape a function expected. That failure mode still exists in dynamically typed code, it just waits until runtime, and often until production, to introduce itself.</p>

      <blockquote>"A compiler error at 2pm is an inconvenience. The same mistake at 2am, paged from an on-call alert, is a different kind of problem entirely."</blockquote>

      <h3>Where it stops helping</h3>
      <p>Types are a poor substitute for tests when the bug is a logic error rather than a shape error, two correctly typed values combined the wrong way. They\'re also a poor substitute for documentation when the type itself is too generic to communicate intent, a string that could be a name, an ID, or a query.</p>

      <p>The honest pitch for static typing isn\'t "correctness." It\'s "a faster, cheaper feedback loop for one specific category of mistake." That\'s a smaller claim, but it\'s the one that holds up under scrutiny, and it\'s enough to justify the choice on its own.</p>'),
(4, 'A Short History of the Coffee Break', 'Elias Novak', '2026-05-10', '2026-05-10', 4, 'Culture', 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1200&q=80', 'The ten-minute pause has a stranger, more contested history than most people assume.', '<p>The phrase "coffee break" didn\'t exist in common use until the middle of the twentieth century, popularized in large part by an advertising campaign for the Pan-American Coffee Bureau. Before that, workplace pauses were informal, uneven, and often unofficial.</p>

      <h3>From privilege to policy</h3>
      <p>In many early twentieth-century factories, pausing to drink coffee was tolerated rather than sanctioned, something workers negotiated for themselves rather than a benefit management offered. It took decades, and considerable organizing, before a short paid break became a standard clause in labor agreements rather than a favor.</p>

      <p>The habit spread well beyond offices that served coffee at all. What people were really institutionalizing was permission: a short, socially acceptable reason to step away from a task without needing to justify it.</p>

      <h3>Why it stuck</h3>
      <p>The specific beverage was almost incidental. What made the coffee break durable is that it gave a whole culture a shared, low-stakes ritual for interrupting focused work on a predictable schedule, something attention needs and rarely gets by design.</p>

      <p>A century later, the ritual has outlived plenty of the workplaces that first adopted it, which says something about how rarely we build genuinely good structures for rest, and how tightly people hold onto the ones that work.</p>'),
(5, 'Debugging Is a Reading Skill', 'Priya Raman', '2026-04-30', '2026-05-02', 7, 'Engineering', 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=1200&q=80', 'The best debuggers aren\'t better guessers. They\'re better at reading what the program is actually telling them.', '<p>Most debugging advice focuses on tools: breakpoints, loggers, profilers. Useful, but secondary. The core skill underneath all of it is closer to reading comprehension than tool proficiency, the ability to read an error, a stack trace, or unexpected output literally, without immediately jumping to a theory about what caused it.</p>

      <h3>The theory trap</h3>
      <p>Experienced engineers aren\'t immune to this. If anything, experience makes it easier to form a plausible theory in the first five seconds, and confirmation bias does the rest, every subsequent clue gets bent to fit that first idea instead of being read on its own terms.</p>

      <blockquote>"Read the error message as if you\'d never seen this codebase before. It usually already told you what\'s wrong."</blockquote>

      <h3>A more disciplined loop</h3>
      <ul>
        <li>Read the failure output line by line before forming any hypothesis.</li>
        <li>State the hypothesis explicitly, out loud or in writing, so it can be checked rather than assumed.</li>
        <li>Design the smallest possible test that would prove the hypothesis wrong, not just one that confirms it.</li>
      </ul>

      <p>This is slower than guessing. It is reliably faster than guessing wrong three times in a row, which is what usually happens when the first theory feels too obviously right to question.</p>'),
(6, 'The Case for Boring Technology', 'Femi Adeyemi', '2026-04-18', '2026-04-18', 6, 'Engineering', 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=1200&q=80', 'Novelty has a budget. Most teams spend it on the wrong part of the stack.', '<p>Every team has a limited amount of "innovation tokens" to spend, a way of thinking that treats novelty as a resource with a real cost, not a free upgrade. Spend them on your product\'s genuine differentiator. Spend them anywhere else and you\'re paying twice: once in engineering time, and again in the operational surprises that come with unfamiliar tools.</p>

      <h3>Boring is a compliment</h3>
      <p>Boring technology means the failure modes are already documented, the community has already hit the edge cases, and the people you hire already know how to operate it. None of that is exciting to write about. All of it is exactly what you want at three in the morning when something breaks.</p>

      <h3>Where novelty earns its keep</h3>
      <p>The exception is the one or two places where your product actually needs to do something genuinely new. That\'s where the innovation tokens belong, concentrated, not spread thin across the database, the framework, the deployment pipeline, and the build tool all at once.</p>

      <p>Choosing boring technology isn\'t a failure of ambition. It\'s a decision to spend ambition where it compounds, instead of scattering it across every layer of the stack just because a newer option existed.</p>'),
(7, 'Notes on Writing Less, Better', 'Maren Solberg', '2026-04-05', '2026-04-07', 5, 'Writing', 'https://images.unsplash.com/photo-1455390582262-044cdead277a?w=1200&q=80', 'Cutting a sentence is usually an act of respect for the reader, not a loss for the writer.', '<p>Every unnecessary word asks something of the reader: a little more time, a little more attention, spent on nothing in particular. Multiply that by a whole document and the cost becomes real, even if no single sentence seems guilty on its own.</p>

      <h3>Cutting is not the same as shrinking</h3>
      <p>The goal isn\'t to make writing shorter for its own sake. Some ideas need room. The goal is to make sure every sentence that survives is doing a job that only it can do, and to remove the ones that are just restating something the reader already understood two sentences ago.</p>

      <blockquote>"If a sentence would go unnoticed by its absence, that\'s the sentence to cut."</blockquote>

      <h3>A useful test</h3>
      <p>Read a paragraph and ask, for each sentence, what would be lost if it were gone. If the honest answer is "nothing," the sentence was decoration, not communication, however nice it sounded while you were writing it.</p>

      <p>This is uncomfortable to practice on your own writing, because the sentences that fail the test are often the ones you liked writing the most. That discomfort is a reasonable price for a reader\'s attention, which is the only resource in this exchange that isn\'t renewable.</p>'),
(8, 'The Map Is Not the Roadmap', 'Elias Novak', '2026-03-22', '2026-03-22', 6, 'Product', 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200&q=80', 'A roadmap describes a plan. It quietly starts to describe a promise if nobody watches it closely.', '<p>A roadmap is supposed to be a working hypothesis about what to build and when, updated as new information arrives. In practice, once a roadmap has been shared outside the team that owns it, it tends to calcify into something closer to a commitment, whether or not anyone intended that shift.</p>

      <h3>Where the drift happens</h3>
      <p>The drift usually isn\'t dramatic. A date meant as a rough estimate gets copied into a sales deck. A "maybe" feature gets referenced in a customer call as though it were confirmed. None of these moments feel like a lie in the room where they happen, but they compound into an expectation the team never actually agreed to.</p>

      <h3>Keeping the plan honest</h3>
      <ul>
        <li>Separate what\'s confirmed from what\'s a current best guess, visibly, not just in someone\'s memory.</li>
        <li>Revisit the roadmap on a schedule, not only when something has already gone wrong.</li>
        <li>Say "no longer planned" out loud instead of letting an item quietly age off the document.</li>
      </ul>

      <p>A roadmap that\'s allowed to change is more useful than one that\'s protected from it, even though the second kind feels more reassuring to read. Certainty you haven\'t earned yet is a liability with a delay on it.</p>');
