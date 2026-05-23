/\*

- ============================================================
- WBS Scheduler — Day-level Greedy + Priority Assignment
- ============================================================
-
- RULES:
- - Working days: Mon–Fri, 8h/day per dev
- - 1 task can be worked on by multiple devs simultaneously
- → hours are additive (2 devs = 16h/day on that task)
- - A dev assigned to task X works it for the full day (8h)
- UNLESS the task finishes mid-day → remaining hours go to
- the next highest-priority available task
- - A task can only START when all its dependencies are DONE
- - Priority selection order: higher priority > more effort > smaller ID
- - Deadline is a specific date (input); timeline beyond deadline
- is still simulated to show the "if we continued" schedule
-
- INPUT FORMAT:
- START_DATE (YYYY-MM-DD)
- DEADLINE (YYYY-MM-DD)
- N M (num devs, num tasks)
- For each task i (1..M):
-      S[i] P[i] K[i] d1 d2 ... dK
-      (effort hours, priority, num deps, dep list)
-
- OUTPUT:
- - If all tasks finish by deadline: "FEASIBLE" + start/end date per task
- - If not: "IMPOSSIBLE" + missing hours + done/in-progress tasks
-              + full schedule continuing past deadline until all done
  \*/

#include <bits/stdc++.h>
using namespace std;

// ─── Date utilities ──────────────────────────────────────────────────────────

/\*

- Simple date representation as days-since-epoch (using std::tm / mktime).
- We convert between "YYYY-MM-DD" strings and integer day offsets throughout.
  \*/

// Parse "YYYY-MM-DD" → time_t (seconds since epoch, 00:00 UTC)
time_t parseDate(const string& s) {
struct tm t = {};
sscanf(s.c_str(), "%d-%d-%d", &t.tm_year, &t.tm_mon, &t.tm_mday);
t.tm_year -= 1900;
t.tm_mon -= 1;
t.tm_isdst = -1;
return mktime(&t);
}

// time_t → "YYYY-MM-DD"
string formatDate(time_t t) {
struct tm\* tm_info = localtime(&t);
char buf[12];
strftime(buf, sizeof(buf), "%Y-%m-%d", tm_info);
return string(buf);
}

// Advance a time_t by `days` calendar days
time_t addDays(time_t base, int days) {
return base + (time_t)days \* 86400;
}

// Return day-of-week: 0=Sun, 1=Mon, ..., 6=Sat
int weekday(time_t t) {
struct tm\* tm_info = localtime(&t);
return tm_info->tm_wday;
}

// Check whether a date is a working day (Mon–Fri)
bool isWorkday(time_t t) {
int wd = weekday(t);
return wd >= 1 && wd <= 5;
}

// Iterate to the next working day (skip weekends)
time_t nextWorkday(time_t t) {
t = addDays(t, 1);
while (!isWorkday(t)) t = addDays(t, 1);
return t;
}

// Count working days between two dates [from, to] inclusive
int workdaysBetween(time_t from, time_t to) {
int count = 0;
time_t cur = from;
while (cur <= to + 1) { // +1 to handle mktime rounding
if (isWorkday(cur)) count++;
cur = addDays(cur, 1);
}
return count;
}

// ─── Task structure ───────────────────────────────────────────────────────────

struct Task {
int id;
int totalHours; // original effort
int remaining; // hours left to complete
int priority;
vector<int> deps; // tasks this one depends on
vector<int> children; // tasks that depend on this one
int indegree; // number of unfinished dependencies

    time_t startDate = -1;  // first day a dev touches this task
    time_t endDate   = -1;  // day the task is completed

    bool done       = false;
    bool inProgress = false; // at least one dev is currently assigned

    // How many devs are working on it right now (this day)
    int activeDevs  = 0;

};

// ─── Comparator for the ready-queue ──────────────────────────────────────────

/\*

- Max-heap ordering:
- 1.  Higher priority wins
- 2.  Tie → larger remaining effort wins (long tasks get started early)
- 3.  Tie → smaller ID wins (deterministic)
      _/
      struct Compare {
      bool operator()(const Task_ a, const Task\* b) const {
      if (a->priority != b->priority) return a->priority < b->priority;
      if (a->remaining != b->remaining) return a->remaining < b->remaining;
      return a->id > b->id;
      }
      };

// ─── Helpers ─────────────────────────────────────────────────────────────────

// Rebuild the ready priority-queue from a vector (needed after mid-day updates)
priority_queue<Task*, vector<Task*>, Compare>
buildReadyQueue(const vector<Task*>& readyList) {
priority_queue<Task*, vector<Task*>, Compare> pq;
for (Task* t : readyList) pq.push(t);
return pq;
}

// ─── Main ─────────────────────────────────────────────────────────────────────

int main() {
ios::sync_with_stdio(false);
cin.tie(nullptr);

    // ── Input ────────────────────────────────────────────────────────────────
    string startStr, deadlineStr;
    cin >> startStr >> deadlineStr;

    int N, M;
    cin >> N >> M;

    time_t startDate    = parseDate(startStr);
    time_t deadlineDate = parseDate(deadlineStr);

    // Advance startDate to first workday if it lands on a weekend
    while (!isWorkday(startDate)) startDate = addDays(startDate, 1);

    vector<Task> tasks(M + 1);
    for (int i = 1; i <= M; i++) {
        int S, P, K;
        cin >> S >> P >> K;

        tasks[i].id         = i;
        tasks[i].totalHours = S;
        tasks[i].remaining  = S;
        tasks[i].priority   = P;
        tasks[i].indegree   = K;

        for (int j = 0; j < K; j++) {
            int d; cin >> d;
            tasks[i].deps.push_back(d);
            tasks[d].children.push_back(i);
        }
    }

    // ── Initialise ready set ─────────────────────────────────────────────────
    // Tasks with no dependencies are immediately available
    vector<Task*> readyList;
    for (int i = 1; i <= M; i++) {
        if (tasks[i].indegree == 0) readyList.push_back(&tasks[i]);
    }

    // ── Simulation state ─────────────────────────────────────────────────────

    /*
     * activeTasks: tasks that have been started and are not yet done.
     * Each entry = pointer to Task. Multiple devs can share the same task.
     *
     * devTask[d] = which task dev d is currently assigned to (-1 if free).
     * We use 0-indexed dev IDs internally.
     */
    vector<Task*> activeTasks; // tasks currently in-flight
    vector<int>   devTask(N, -1); // dev assignment for the current day

    int totalDone      = 0;
    bool deadlinePassed = false;

    // Log buffer — printed after simulation
    vector<string> logLines;

    auto log = [&](const string& s) { logLines.push_back(s); };

    // ── Day loop ──────────────────────────────────────────────────────────────
    time_t day = startDate;

    // We simulate until every task is done (may exceed deadline)
    while (totalDone < M) {

        if (!isWorkday(day)) { day = addDays(day, 1); continue; }

        string dayStr = formatDate(day);
        bool   past   = (day > deadlineDate);
        if (past && !deadlinePassed) {
            deadlinePassed = true;
            log(">>> DEADLINE PASSED: continuing simulation to finish all tasks <<<");
        }

        log("\n=== " + dayStr + (past ? " [PAST DEADLINE]" : "") + " ===");

        // ── Step 1: Admit new ready tasks into activeTasks ───────────────────
        /*
         * Any task whose indegree just hit 0 (all deps done) joins the
         * active pool. We sort readyList by priority so admission is ordered,
         * though actual assignment happens in Step 2.
         */
        sort(readyList.begin(), readyList.end(), [](Task* a, Task* b){
            if (a->priority != b->priority) return a->priority > b->priority;
            if (a->remaining != b->remaining) return a->remaining > b->remaining;
            return a->id < b->id;
        });
        for (Task* t : readyList) {
            if (!t->inProgress && !t->done) {
                activeTasks.push_back(t);
                t->inProgress = true;
                log("  [ADMIT] Task " + to_string(t->id) +
                    " admitted (priority=" + to_string(t->priority) +
                    ", remaining=" + to_string(t->remaining) + "h)");
            }
        }
        readyList.clear();

        // Sort activeTasks by priority (highest first) for dev assignment
        sort(activeTasks.begin(), activeTasks.end(), [](Task* a, Task* b){
            if (a->priority != b->priority) return a->priority > b->priority;
            if (a->remaining != b->remaining) return a->remaining > b->remaining;
            return a->id < b->id;
        });

        // ── Step 2: Assign devs to tasks ─────────────────────────────────────
        /*
         * Each dev gets 8h today. We assign greedily:
         *   - First, give every active task at least one dev (if devs available)
         *   - Then, any remaining devs help the highest-priority active task
         *     (the "dev rảnh nhảy vào" rule)
         *
         * devTask[d] stores the task ID assigned to dev d for this day.
         */
        fill(devTask.begin(), devTask.end(), -1);

        // Pass 1: assign one dev per task (in priority order)
        int devIdx = 0;
        for (Task* t : activeTasks) {
            if (devIdx >= N) break;
            devTask[devIdx] = t->id;
            devIdx++;
        }

        // Pass 2: spare devs → help the highest-priority active task
        if (!activeTasks.empty()) {
            while (devIdx < N) {
                // Highest-priority active task is activeTasks[0]
                devTask[devIdx] = activeTasks[0]->id;
                devIdx++;
            }
        }

        // Reset per-day dev counter on tasks
        for (Task* t : activeTasks) t->activeDevs = 0;
        for (int d = 0; d < N; d++) {
            if (devTask[d] != -1) tasks[devTask[d]].activeDevs++;
        }

        // ── Step 3: Execute work ──────────────────────────────────────────────
        /*
         * For each task, total hours contributed today = activeDevs × 8.
         * However, a task cannot receive more hours than it needs.
         *
         * If a task finishes mid-day, the "leftover" hours from its devs
         * are redistributed to the next priority task. We handle this by
         * processing tasks in priority order and propagating leftover hours.
         *
         * Leftover pool: when task T finishes, each of its devs has
         * (8 - hoursSpentOnT) leftover hours. These roll to the next task.
         */

        // Log initial assignments (printed once per day)
        for (int d = 0; d < N; d++) {
            if (devTask[d] != -1) {
                log("  [ASSIGN] Dev " + to_string(d+1) +
                    " → Task " + to_string(devTask[d]));
            }
        }

        // We'll track remaining dev-hours as a pool
        // devHours[d] = hours dev d has left to spend today
        vector<int> devHours(N, 8);

        // Process in priority order; tasks may "absorb" hours from freed devs
        // We loop until all dev-hours for today are consumed or no tasks left
        bool anyWorkDone = true;
        bool firstWorkPass = true;
        while (anyWorkDone) {
            anyWorkDone = false;
            firstWorkPass = false;

            // Rebuild sorted active list (tasks might have finished mid-loop)
            sort(activeTasks.begin(), activeTasks.end(), [](Task* a, Task* b){
                if (a->priority != b->priority) return a->priority > b->priority;
                if (a->remaining != b->remaining) return a->remaining > b->remaining;
                return a->id < b->id;
            });

            // Check if any dev has hours left
            bool anyDevsLeft = false;
            for (int d = 0; d < N; d++) if (devHours[d] > 0) anyDevsLeft = true;
            if (!anyDevsLeft || activeTasks.empty()) break;

            for (Task* t : activeTasks) {
                if (t->done) continue;

                // Collect devs currently assigned to this task with hours left
                vector<int> myDevs;
                for (int d = 0; d < N; d++) {
                    if (devTask[d] == t->id && devHours[d] > 0) myDevs.push_back(d);
                }
                if (myDevs.empty()) continue;

                // Record start date
                if (t->startDate == (time_t)-1) t->startDate = day;

                // Hours this task will receive today from its assigned devs
                // Each dev contributes min(devHours[d], share) where share is
                // computed so we don't overshoot t->remaining
                int totalContrib = 0;
                // First pass: how many hours can we give?
                int hoursAvail = 0;
                for (int d : myDevs) hoursAvail += devHours[d];
                int hoursGiven = min(hoursAvail, t->remaining);

                // Distribute hoursGiven proportionally (each dev gives equally
                // until task is satisfied or dev runs out)
                int leftToGive = hoursGiven;
                for (int d : myDevs) {
                    if (leftToGive <= 0) break;
                    int give = min(devHours[d], leftToGive);
                    devHours[d] -= give;
                    leftToGive  -= give;
                    totalContrib += give;
                }

                t->remaining -= totalContrib;

                log("  [WORK] Task " + to_string(t->id) +
                    " received " + to_string(totalContrib) + "h " +
                    "(devs: " + [&](){
                        string s;
                        for (int d : myDevs) s += to_string(d+1) + " ";
                        return s;
                    }() + ")" +
                    " → remaining=" + to_string(t->remaining) + "h");

                anyWorkDone = true;

                // ── Task finished? ───────────────────────────────────────────
                if (t->remaining == 0) {
                    t->done    = true;
                    t->endDate = day;
                    totalDone++;

                    log("  [DONE] Task " + to_string(t->id) +
                        " completed! start=" + formatDate(t->startDate) +
                        " end=" + formatDate(t->endDate));

                    // Unlock children whose indegree drops to 0
                    for (int nxt : t->children) {
                        tasks[nxt].indegree--;
                        if (tasks[nxt].indegree == 0) {
                            readyList.push_back(&tasks[nxt]);
                            log("  [UNLOCK] Task " + to_string(nxt) +
                                " is now ready (all deps done)");
                        }
                    }

                    // Devs that were on this task now have free hours:
                    // reassign them to the next best task for the remainder
                    // of the day. We do this by updating devTask[d] to the
                    // new top-priority active (non-done) task.
                    Task* nextTask = nullptr;
                    for (Task* candidate : activeTasks) {
                        if (!candidate->done) { nextTask = candidate; break; }
                    }
                    // Also check newly-readied tasks (they aren't in activeTasks yet)
                    // We'll admit them at the top of next iteration's Step 1,
                    // but if this is mid-day we handle it inline:
                    for (Task* r : readyList) {
                        if (!nextTask ||
                            r->priority > nextTask->priority ||
                            (r->priority == nextTask->priority && r->remaining > nextTask->remaining) ||
                            (r->priority == nextTask->priority && r->remaining == nextTask->remaining && r->id < nextTask->id)) {
                            nextTask = r;
                        }
                    }

                    for (int d : myDevs) {
                        if (devHours[d] > 0) {
                            if (nextTask) {
                                devTask[d] = nextTask->id;
                                log("  [REASSIGN] Dev " + to_string(d+1) +
                                    " → Task " + to_string(nextTask->id) +
                                    " (" + to_string(devHours[d]) + "h left)");
                            } else {
                                devTask[d] = -1; // no work available
                                log("  [IDLE] Dev " + to_string(d+1) +
                                    " has " + to_string(devHours[d]) + "h with no task to do");
                            }
                        }
                    }

                    // If newly-admitted tasks have devs now, mark inProgress
                    if (nextTask && !nextTask->inProgress) {
                        nextTask->inProgress = true;
                        if (find(activeTasks.begin(), activeTasks.end(), nextTask) == activeTasks.end())
                            activeTasks.push_back(nextTask);
                        // Remove from readyList since we've admitted it
                        readyList.erase(
                            remove(readyList.begin(), readyList.end(), nextTask),
                            readyList.end()
                        );
                    }
                }
            }

            // Remove done tasks from activeTasks
            activeTasks.erase(
                remove_if(activeTasks.begin(), activeTasks.end(), [](Task* t){ return t->done; }),
                activeTasks.end()
            );
        }

        // Advance to next day
        day = addDays(day, 1);
    }

    // ── Compute summary stats ─────────────────────────────────────────────────

    // Total hours missing if any task not done by deadline
    // (We already simulate past deadline, so check which tasks ended after it)
    bool feasible = true;
    int  missingHours = 0;
    for (int i = 1; i <= M; i++) {
        if (tasks[i].endDate > deadlineDate) {
            feasible = false;
            // Approximate missing hours = remaining at deadline (not tracked separately,
            // so we use a rough estimate: proportional to how far past deadline it ran)
        }
    }

    // ── Print logs ────────────────────────────────────────────────────────────
    cout << "\n";
    cout << "============================================================\n";
    cout << "  WBS SCHEDULER — DAILY LOG\n";
    cout << "============================================================\n";
    for (const string& l : logLines) cout << l << "\n";

    // ── Print summary ─────────────────────────────────────────────────────────
    cout << "\n============================================================\n";
    cout << "  SUMMARY\n";
    cout << "============================================================\n";
    cout << "Start date   : " << startStr << "\n";
    cout << "Deadline     : " << deadlineStr << "\n";
    cout << "Developers   : " << N << "\n";
    cout << "Tasks        : " << M << "\n\n";

    if (feasible) {
        cout << "STATUS: FEASIBLE ✓\n\n";
        cout << left << setw(8) << "Task"
             << setw(14) << "Start"
             << setw(14) << "End"
             << setw(10) << "Effort(h)"
             << "Priority\n";
        cout << string(56, '-') << "\n";
        for (int i = 1; i <= M; i++) {
            cout << left << setw(8) << i
                 << setw(14) << formatDate(tasks[i].startDate)
                 << setw(14) << formatDate(tasks[i].endDate)
                 << setw(10) << tasks[i].totalHours
                 << tasks[i].priority << "\n";
        }
    } else {
        cout << "STATUS: IMPOSSIBLE ✗\n\n";

        // Tasks done by deadline
        cout << "── Tasks completed by deadline ──\n";
        int doneCount = 0;
        for (int i = 1; i <= M; i++) {
            if (tasks[i].endDate <= deadlineDate) {
                cout << "  Task " << i << " : done on " << formatDate(tasks[i].endDate) << "\n";
                doneCount++;
            }
        }
        if (doneCount == 0) cout << "  (none)\n";

        // Tasks in-progress or not started at deadline
        cout << "\n── Tasks NOT completed by deadline ──\n";
        for (int i = 1; i <= M; i++) {
            if (tasks[i].endDate > deadlineDate || !tasks[i].done) {
                string status = tasks[i].inProgress ? "in-progress" : "not-started";
                if (tasks[i].startDate != (time_t)-1 && tasks[i].startDate <= deadlineDate)
                    status = "in-progress";
                else if (tasks[i].startDate == (time_t)-1 || tasks[i].startDate > deadlineDate)
                    status = "not-started";
                cout << "  Task " << i << " [" << status << "]"
                     << " : completed " << formatDate(tasks[i].endDate)
                     << " (" << tasks[i].totalHours << "h total)\n";
            }
        }

        // Estimate total missing hours (working hours between deadline+1 and last end date)
        time_t lastEnd = deadlineDate;
        for (int i = 1; i <= M; i++) if (tasks[i].endDate > lastEnd) lastEnd = tasks[i].endDate;
        int extraWorkdays = workdaysBetween(addDays(deadlineDate, 1), lastEnd);
        missingHours = extraWorkdays * N * 8;
        cout << "\nEstimated extra work needed beyond deadline: ~"
             << missingHours << "h (" << extraWorkdays << " workdays × "
             << N << " devs × 8h)\n";

        // Full extended schedule
        cout << "\n── Extended schedule (past deadline) ──\n";
        cout << left << setw(8) << "Task"
             << setw(14) << "Start"
             << setw(14) << "End"
             << setw(10) << "Effort(h)"
             << "Status\n";
        cout << string(56, '-') << "\n";
        for (int i = 1; i <= M; i++) {
            string status = (tasks[i].endDate <= deadlineDate) ? "on-time" : "LATE";
            cout << left << setw(8) << i
                 << setw(14) << formatDate(tasks[i].startDate)
                 << setw(14) << formatDate(tasks[i].endDate)
                 << setw(10) << tasks[i].totalHours
                 << status << "\n";
        }
    }

    cout << "\n============================================================\n";
    return 0;

}
