# Agentic Coding Notes

I wound up using Claude twice throughout the project. Since I'm not as well versed with Claude and agentic tools, I chose to stick with the default configured agent (Sonnet 4.5). I'd say my results from these prompts were somewhat mixed but overall successful.

## Creating a Profile Fetcher Service

The prompt I gave Claude was the following:

> Create a profile fetcher service. It should use Laravel's HTTP client to fetch all profiles from JSONPlaceholder and return them as a collection of ProfileData objects. Make sure the the base url for JSONPlaceholder can be modified via config files. Make sure the phone number is properly split between the phone and extension and properly formatted for the ProfileData object. Create unit tests for this service as well

The resulting class very good and for the most part well thought out, albeit with a few quirks. For example, Claude initially created a function to split the phone number and extension parts, and remove all non-digits from the phone number. Then, after testing the code it realized phone numbers with leading 1s needed to have those trimmed as well so, rather than update the existing cleanup method it created a second one that did just that. Obviously it's not an incorrect solution but it shows a lack of considering the whole picture.

On the other hand, Claude did catch some items that I did *not* instruct it on. For example, it created a `normalizeWebsite()` method that ensured the website URL started with a valid http scheme. I was fairly confident this was going to be required but I had intended to save it for a second pass at the service. Instead, Claude created it from the onset and it's actually one of the only methods I modified little if at all.

Ultimately most of the class's structure remain unchanged but I wound up modifying the body of just about every method in there. Some of that was from bad prompting (I realized I wanted to use dependency injection with a dedicated Placeholder API service rather than the Http facade), some was stylistic preference (rewriting some methods to use single returns with ternary). Overall, it gave me an excellent starting point to finish out the rest of the class myself. That said, while it did create a unit test class, I discarded it entirely in favor of the next prompt:

## Creating a Pest Test

To create a Pest based test file for the previous service, I instructed Claude as follows:

> Create a test file for this class using Laravel Pest. Be sure to cover key edgecases regarding phone, zipcode, and website formatting

The results of this prompt were far less successful in my opinion, and I wound up discarding the entire file and creating it myself. That said, that's not entirely Claude's fault:

- As you can see, I gave it a much less detailed prompt this time so there was less input from me to work with.
- I had not yet modified the original service to use the `PlaceholderApiInterface` at this point so Claude used `Http::fake()` to mock the API calls.

The resulting Pest file was lengthy and filled with repeated steps to set up fake API responses. Interestingly, you could almost see Claude getting "tired" as the file went on. Initial mock responses were complete profiles taken almost verbatim from JSONPlaceholder API. Later ones contained users named "Test" with email addresses like "test@test.com". Eventually values were just single character. That all said, the size and verbosity of the file made comprehending it more of a challenge and ultimately led me to choose to recreate it from scratch myself.

I believe had I gone back to refactor the Profile Fetcher service myself first and then asked Claude to update the test cases I may have been happier with the results and kept at least some of its output. The decision to scrap it and create it myself however was informed by another factor: I've never used Pest in a project so I wanted this to be an opportunity to learn more about it. I had hoped the file Claude created would be a good starting point for that but ultimately decided starting small and building iteratively myself would be a better learning experience.
