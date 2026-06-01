# CLAUDE.md

## Project

Nền tảng chia sẻ kiến thức trực tuyến / Diễn đàn cộng đồng

---

# Overview

Dự án này là một nền tảng chia sẻ kiến thức trực tuyến có khả năng mở rộng, tập trung vào:

- Community discussions
- Content sharing
- Moderation
- Security

Primary stack:

- Laravel
- MySQL/PostgreSQL

---

# Skills

@./skills/authentication.skill.md
@./skills/post-management.skill.md
@./skills/comment.skill.md
@./skills/moderation.skill.md
@./skills/search.skill.md
@./skills/security.skill.md
@./skills/testing.skill.md

---

# Agents

@./agents/backend.agent.md
@./agents/frontend.agent.md
@./agents/database.agent.md
@./agents/security.agent.md
@./agents/moderation.agent.md
@./agents/devops.agent.md

---

# Rules

@./rules/coding.rules.md
@./rules/security.rules.md
@./rules/architecture.rules.md
@./rules/git-workflow.rules.md
@./rules/api.rules.md
@./rules/testing.rules.md

---

# Docs

Tài liệu yêu cầu

@./docs/srs/SRS.md

Tài liệu Basic Design

@./docs/bd/3.1.2.BD.md

Tài liệu Detail Design

@./docs/dd/3.1.2.DD.md

---

# Architecture

@./architect/laravel.architect.md
@./architect/database.architect.md

---

# Relations

| Skill | Agent(s) | Rules | Architect |
|-------|----------|-------|-----------|
| authentication | backend, security | security, coding, api | laravel, database |
| post-management | backend, frontend, database | coding, api, architecture | laravel, database |
| comment | backend, frontend, database | coding, api, security | laravel, database |
| moderation | moderation, backend | architecture, coding | laravel |
| search | backend, database | api, architecture | database |
| security | security | security | laravel |
| testing | backend, frontend | testing | laravel |
